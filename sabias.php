<?php
include './bd/conexion.php';

$UUID_doc = 'security-practices-001';
$stmt = $conexion->prepare("SELECT html_content FROM editor_docs WHERE UUID_doc = ?");
$stmt->bind_param("s", $UUID_doc);
$stmt->execute();
$result = $stmt->get_result();
$contenido = $result->fetch_assoc()['html_content'] ?? '<p><em>Documento no encontrado.</em></p>';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Sabias que..</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/png" sizes="192x192" href="./img/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <style>
   body {
  background: linear-gradient(135deg, #3b82f6, #6366f1, #c7d2fe);
  background-size: 500% 500%;
  animation: gradientShift 20s ease infinite;
  font-family: 'Segoe UI', sans-serif;
  padding: 2rem;
}

@keyframes gradientShift {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}



    .content-view {
      max-width: 880px;
      margin: auto;
      background: rgba(255, 255, 255, 0.7);
      border-radius: 1rem;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
      padding: 3rem;
      font-size: 1.1rem;
      line-height: 1.8;
      color: #1e293b;
      border: 2px solid rgba(255, 255, 255, 0.4);
      backdrop-filter: blur(10px);
    }

    .content-view h1,
    .content-view h2 {
      font-weight: 700;
      color: #9333ea;
      border-bottom: 2px dashed #e9d5ff;
      padding-bottom: 0.5rem;
      margin-top: 2rem;
    }

    .editor-container {
      max-width: 940px;
      margin: auto;
      background: white;
      border-radius: 12px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
      padding: 2rem;
      display: none;
    }

    #toolbar {
      margin-bottom: 1rem;
      border-radius: 8px 8px 0 0;
      background-color: #f9fafb;
      padding: 0.75rem;
      border: 1px solid #ddd;
    }

    #editor {
      border: 1px solid #e2e8f0;
      border-radius: 0 0 12px 12px;
      min-height: 450px;
      padding: 1rem;
      font-size: 1.1rem;
    }

    .btn-floating {
      background: #4f46e5;
      color: white;
      border: none;
      border-radius: 30px;
      padding: 0.6rem 1.2rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .btn-floating:hover {
      background: #3730a3;
      transform: translateY(-2px);
    }

    .btn-save {
      background-color: #10b981;
    }

    .btn-save:hover {
      background-color: #059669;
    }
  </style>
</head>
<body>

  <!-- Botones flotantes -->
  <div class="position-fixed" style="right: 1.5rem; bottom: 2rem; display: flex; flex-direction: column; gap: 1rem; z-index: 1000;">

    <!-- Botón Atrás -->
    <button onclick="history.back()" class="btn-floating">
      <i class="bi bi-arrow-left-circle"></i> Atrás
    </button>

    <!-- Botón Editar -->
    <button id="btnEdit" onclick="enableEdit()" class="btn-floating">
      <i class="bi bi-pencil-square"></i> Editar
    </button>

    <!-- Botón Guardar -->
    <button id="btnSave" class="btn-floating btn-save d-none">
      <i class="bi bi-save2"></i> Guardar
    </button>

  </div>

  <!-- Vista solo lectura -->
  <div id="viewMode" class="content-view">
    <?= $contenido ?>
  </div>

  <!-- Editor -->
    <div class="editor-container" id="editorContainer">
    <div id="toolbar">
      <span class="ql-formats">
        <button class="ql-bold"></button>
        <button class="ql-italic"></button>
        <button class="ql-underline"></button>
      </span>
      <span class="ql-formats">
        <button class="ql-list" value="ordered"></button>
        <button class="ql-list" value="bullet"></button>
      </span>
      <span class="ql-formats">
        <select class="ql-align"></select>
        <select class="ql-color"></select>
      </span>
      <span class="ql-formats">
        <button class="ql-link"></button>
      </span>
    </div>
    <div id="editor"></div>
  </div>

  <!-- Quill y lógica -->
  <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
  <script>
    let quill;
    const uuid = "<?= $UUID_doc ?>";

    function enableEdit() {
      document.getElementById('viewMode').style.display = 'none';
      document.getElementById('btnEdit').style.display = 'none';
      document.getElementById('editorContainer').style.display = 'block';
      document.getElementById('btnSave').classList.remove('d-none');

      if (!quill) {
        quill = new Quill('#editor', {
          modules: { toolbar: '#toolbar' },
          theme: 'snow'
        });
        quill.clipboard.dangerouslyPasteHTML(document.getElementById('viewMode').innerHTML);
      }
    }

    document.getElementById('btnSave').addEventListener('click', () => {
      const content = quill.root.innerHTML;
      fetch('guardar_editor.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ uuid, title: 'Política USB', content })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'ok') {
          alert('✅ Documento actualizado');
          location.reload();
        } else {
          alert('❌ Error: ' + data.message);
        }
      });
    });
  </script>
</body>

</html>