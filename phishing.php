<?php
include './bd/conexion.php';

$UUID_doc = 'phishing-001';
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
  <title>Phishing</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="icon" type="image/png" sizes="192x192" href="./img/logo.png">
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
  body {
    background: linear-gradient(to bottom right, #7f1d1d, #0f172a); /* Rojo oscuro a azul muy oscuro */
    font-family: 'Segoe UI', sans-serif;
    padding: 2rem;
  }

  .content-view {
    max-width: 880px;
    margin: auto;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 1rem;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
    padding: 3rem;
    font-size: 1.1rem;
    line-height: 1.8;
    color: #1e293b;
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(12px);
  }

  .editor-container {
    max-width: 940px;
    margin: auto;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    padding: 2rem;
    display: none;
    animation: fadeIn 0.3s ease-in-out;
  }

  #toolbar {
    margin-bottom: 1rem;
    background-color: #f8fafc;
    border-radius: 12px 12px 0 0;
    border: 1px solid #e2e8f0;
    padding: 0.75rem;
  }

  #editor {
    border: 1px solid #e2e8f0;
    border-radius: 0 0 12px 12px;
    min-height: 450px;
    padding: 1.5rem;
    font-size: 1.1rem;
    line-height: 1.8;
    background-color: #ffffff;
    color: #1f2937;
  }

  .btn-floating {
    background: #dc2626;
    color: white;
    border: none;
    border-radius: 30px;
    padding: 0.6rem 1.2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    cursor: pointer;
    transition: all 0.2s ease;
    font-weight: 500;
  }

  .btn-floating:hover {
    background: #991b1b;
    transform: translateY(-2px);
  }

  .btn-save {
    background-color: #16a34a;
  }

  .btn-save:hover {
    background-color: #15803d;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
  }
</style>



</head>
<body>

<!-- Botones flotantes -->
<div class="position-fixed" style="right: 1.5rem; bottom: 2rem; display: flex; flex-direction: column; gap: 1rem; align-items: flex-end; z-index: 1000;">
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

<!-- Vista de lectura -->
<div id="viewMode" class="content-view">
  <?= $contenido ?>
</div>

<!-- Editor oculto -->
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
      body: JSON.stringify({ uuid, title: 'Seguridad Informática: Phishing', content })
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
