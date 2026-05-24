<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elegance Administration Dashboard Engine</title>
    <style>
        :root { --gold: #c5a059; --dark: #111; --border: #ddd; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #faf9f6; margin: 0; padding: 20px; color: #333; }
        .dashboard-frame { max-width: 750px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border-top: 4px solid var(--gold); }
        .field-unit { margin-bottom: 20px; }
        label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; }
        input[type="text"], textarea { width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 4px; box-sizing: border-box; font-size: 0.95rem; }
        textarea { height: 110px; resize: vertical; font-family: inherit; }
        .action-btn { background: var(--dark); color: #fff; border: none; padding: 14px; width: 100%; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 1rem; transition: background 0.3s; }
        .action-btn:hover { background: var(--gold); }
        .notification-tray { padding: 15px; border-radius: 4px; margin-bottom: 20px; display: none; font-weight: bold; font-size: 0.95rem; }
        .notification-tray.success { background: #e6f4ea; color: #137333; border: 1px solid #137333; }
        .notification-tray.error { background: #fce8e6; color: #c5221f; border: 1px solid #c5221f; }
        #panel-loader { color: var(--gold); font-weight: bold; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="dashboard-frame">
    <h2>Elegance Core CMS: About Layout Editor</h2>
    <p style="color:#777; font-size:0.85rem; margin-bottom:25px;">Safely mutate presentation layers directly via programmatic schema operations.</p>
    
    <div id="panel-loader">Retrieving current state parameters from API engine...</div>
    <div id="status-tray" class="notification-tray"></div>

    <form id="cmsForm" style="display:none;">
        <div class="field-unit">
            <label>Registered Identity Name</label>
            <input type="text" id="company_name" required>
        </div>
        <div class="field-unit">
            <label>Structural Brand Description</label>
            <textarea id="description" required></textarea>
        </div>
        <div class="field-unit">
            <label>Mission Directive</label>
            <textarea id="mission" required></textarea>
        </div>
        <div class="field-unit">
            <label>Strategic Vision Goals</label>
            <textarea id="vision" required></textarea>
        </div>
        <div class="field-unit">
            <label>Active Showcase Asset Image URL</label>
            <input type="text" id="image_url" required>
        </div>
        <button type="submit" class="action-btn">Commit Changes & Synchronize</button>
    </form>
</div>

<script>
    const apiEndpoint = 'api.php';
    const form = document.getElementById('cmsForm');
    const loader = document.getElementById('panel-loader');
    const tray = document.getElementById('status-tray');

    async function loadFormDefaults() {
        try {
            const res = await fetch(apiEndpoint);
            if (!res.ok) throw new Error("Could not parse endpoint matrix.");
            const data = await res.json();
            
            document.getElementById('company_name').value = data.company_name;
            document.getElementById('description').value = data.description;
            document.getElementById('mission').value = data.mission;
            document.getElementById('vision').value = data.vision;
            document.getElementById('image_url').value = data.image_url;

            loader.style.display = 'none';
            form.style.display = 'block';
        } catch (err) {
            loader.innerText = "Error tracking base structure values.";
            showNotification(err.message, 'error');
        }
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        showNotification("Pushing structural changes directly to API tables...", 'success');

        const payload = {
            company_name: document.getElementById('company_name').value,
            description: document.getElementById('description').value,
            mission: document.getElementById('mission').value,
            vision: document.getElementById('vision').value,
            image_url: document.getElementById('image_url').value
        };

        try {
            const response = await fetch(apiEndpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await response.json();
            
            if (result.status === 'success') {
                showNotification(result.message, 'success');
            } else {
                showNotification(result.message, 'error');
            }
        } catch (err) {
            showNotification("Data packet rejection: " + err.message, 'error');
        }
    });

    function showNotification(msg, type) {
        tray.className = `notification-tray ${type}`;
        tray.innerText = msg;
        tray.style.display = 'block';
    }

    document.addEventListener('DOMContentLoaded', loadFormDefaults);
</script>
</body>
</html>
