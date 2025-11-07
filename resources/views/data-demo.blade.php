<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Data Demo</title>
    <style>body{font-family:system-ui,Segoe UI,Roboto,Arial;padding:20px}pre{background:#f5f5f5;padding:10px;border-radius:6px}</style>
</head>
<body>
    <h1>Data Demo</h1>
    <button id="load-ppdb">Load PPDB</button>
    <pre id="ppdb-result">-</pre>

    <button id="load-bagus">Load Bagus</button>
    <pre id="bagus-result">-</pre>

    <script>
    async function fetchJson(path){
        const res = await fetch(path, {headers:{'Accept':'application/json'}});
        return res.json();
    }

    document.getElementById('load-ppdb').addEventListener('click', async ()=>{
        const data = await fetchJson('/api/data/ppdb');
        document.getElementById('ppdb-result').textContent = JSON.stringify(data, null, 2);
    });

    document.getElementById('load-bagus').addEventListener('click', async ()=>{
        const data = await fetchJson('/api/data/bagus');
        document.getElementById('bagus-result').textContent = JSON.stringify(data, null, 2);
    });
    </script>
</body>
</html>
