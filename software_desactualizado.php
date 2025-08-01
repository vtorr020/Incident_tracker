<?php
include './bd/conexion.php';

$UUID_doc = '38fe7751-5af9-11f0-82d3-5254007e02a0';

$sql = "SELECT html_content FROM editor_docs WHERE UUID_doc = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $UUID_doc);
$stmt->execute();
$result = $stmt->get_result();
$contenido = $result->fetch_assoc()['html_content'] ?? "<p><em>No se encontró el documento.</em></p>";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Software Desactualizado - Foundever</title>

  <!-- ✅ Bootstrap (para que funcione d-none) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/png" sizes="192x192" href="./img/logo.png">

  <!-- Quill y Bootstrap Icons -->
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

<style>
  body {
    background: linear-gradient(to right, #1e3a8a, #0ea5e9); /* Azul oscuro a celeste */
    padding: 2rem;
    font-family: 'Segoe UI', sans-serif;
    color: #f1f5f9;
  }

  .container {
    max-width: 950px;
    margin: auto;
    background: #f8fafc;
    padding: 2.5rem 3rem;
    border-radius: 1rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    font-size: 1.1rem;
    line-height: 1.8;
    color: #1e293b;
  }

  .editor-container {
    max-width: 950px;
    margin: auto;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
    padding: 2rem 2.5rem;
    display: none;
  }

  #toolbar {
    margin-bottom: 1rem;
    border: 1px solid #cbd5e1;
    border-radius: 8px 8px 0 0;
    background-color: #f1f5f9;
    padding: 0.75rem;
  }

  #editor {
    font-family: 'Georgia', 'Segoe UI', serif;
    font-size: 1.1rem;
    line-height: 1.7;
    padding: 2rem;
    color: #1f2937;
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0 0 12px 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    min-height: 500px;
  }

  /* Botones flotantes */
  .btn-floating {
    background: linear-gradient(to right, #6366f1, #3b82f6); /* degradado azul violeta */
    color: #fff;
    border: none;
    border-radius: 30px;
    padding: 0.8rem 1.4rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
    cursor: pointer;
    transition: all 0.2s ease;
    font-weight: bold;
    font-size: 1rem;
  }

  .btn-floating:hover {
    transform: translateY(-3px);
    background: linear-gradient(to right, #4f46e5, #2563eb);
  }

  .btn-save {
    background: linear-gradient(to right, #10b981, #34d399); /* verde vibrante */
  }

  .btn-save:hover {
    background: linear-gradient(to right, #059669, #22c55e);
  }

  .button-panel {
    position: fixed;
    right: 1.5rem;
    bottom: 2rem;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 1rem;
    z-index: 999;
  }
</style>



</head>
<body>

  <!-- Botones flotantes alineados -->
  <div class="button-panel">
    <button onclick="history.back()" class="btn-floating">
      <i class="bi bi-arrow-left-circle"></i> Atrás
    </button>
    <button id="btnEdit" onclick="enableEdit()" class="btn-floating">
      <i class="bi bi-pencil-square"></i> Editar
    </button>
 <button id="btnSave" class="btn-floating btn-save d-none">
  <i class="bi bi-save2"></i> Guardar
</button>

  </div>

  <!-- Vista solo lectura -->
  <main class="container" id="viewMode">
    <?= $contenido ?>
  </main>

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

  <!-- Scripts -->
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
        body: JSON.stringify({
          uuid: uuid,
          title: 'Software Desactualizado',
          content: content
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'ok') {
          alert('✅ Documento guardado correctamente');
          location.reload();
        } else {
          alert('❌ Error al guardar: ' + data.message);
        }
      });
    });
  </script>
</body>
</html>
