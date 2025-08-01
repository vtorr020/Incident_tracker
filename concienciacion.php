<?php
include './bd/conexion.php';

$UUID_doc = 'awareness-policy-001';
$stmt = $conexion->prepare("SELECT html_content FROM editor_docs WHERE UUID_doc = ?");
$stmt->bind_param("s", $UUID_doc);
$stmt->execute();
$result = $stmt->get_result();
$contenido = $result->fetch_assoc()['html_content'] ?? '<p><em>Documento no encontrado.</em></p>';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Concientización de seguridad</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="icon" type="image/png" sizes="192x192" href="./img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet" />
    <style>

    body {
        background: linear-gradient(to right, #3b82f6, #6366f1); /* azul claro a violeta suave */
        padding: 2rem;
        font-family: 'Segoe UI', sans-serif;
        color: #f3f4f6; /* gris muy claro para el texto */
    }




        .content-view {
            max-width: 880px;
            margin: auto;
            background: #ffffff;
            border-radius: 1rem;
            padding: 3rem;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            color: #1e293b;
        }

        .content-view h1,
        .content-view h2 {
            font-weight: bold;
            margin-top: 2rem;
            color: #0ea5e9;
            border-bottom: 2px dashed #7dd3fc;
            padding-bottom: 0.5rem;
        }

        .editor-container {
            max-width: 880px;
            margin: auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            display: none;
        }

        #toolbar {
            margin-bottom: 1rem;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 0.75rem;
            border-radius: 8px 8px 0 0;
        }

        #editor {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 0 0 12px 12px;
            min-height: 500px;
            padding: 2rem;
            font-size: 1.1rem;
            color: #1f2937;
        }

        .btn-floating {
            background: #0ea5e9;
            color: #fff;
            border: none;
            border-radius: 30px;
            padding: 0.7rem 1.2rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-floating:hover {
            background-color: #0369a1;
            transform: translateY(-3px);
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

    <!-- Botones -->
    <div class="position-fixed" style="right: 1.5rem; bottom: 2rem; display: flex; flex-direction: column; align-items: flex-end; gap: 1rem; z-index: 999;">
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

    <!-- Vista -->
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
                    modules: {
                        toolbar: '#toolbar'
                    },
                    theme: 'snow'
                });
                quill.clipboard.dangerouslyPasteHTML(document.getElementById('viewMode').innerHTML);
            }
        }

        document.getElementById('btnSave').addEventListener('click', () => {
            const content = quill.root.innerHTML;

            fetch('guardar_editor.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        uuid: uuid,
                        title: 'Política de Concientización y Seguridad',
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