<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$miasto = $_GET['miasto'] ?? 'Katowice';
$typ = $_GET['typ'] ?? 'museum'; 
$miastoEscaped = htmlspecialchars($miasto, ENT_QUOTES);
$typEscaped = htmlspecialchars($typ, ENT_QUOTES);

$query = "
[out:json];
area[\"name\"=\"$miastoEscaped\"][\"boundary\"=\"administrative\"]->.searchArea;
(
  node[\"tourism\"=\"$typEscaped\"](area.searchArea);
  way[\"tourism\"=\"$typEscaped\"](area.searchArea);
  relation[\"tourism\"=\"$typEscaped\"](area.searchArea);
);
out center;
";  

$ch = curl_init("https://overpass-api.de/api/interpreter");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: text/plain", "User-Agent: MyTravelApp/1.0"]);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$atrakcje = $data['elements'] ?? [];

function distance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371; 
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $earthRadius * $c;
}

$centerLat = null; $centerLon = null;
if (!empty($atrakcje[0]['lat'])) {
    $centerLat = $atrakcje[0]['lat']; $centerLon = $atrakcje[0]['lon'];
} elseif (!empty($atrakcje[0]['center'])) {
    $centerLat = $atrakcje[0]['center']['lat']; $centerLon = $atrakcje[0]['center']['lon'];
}

if ($centerLat && $centerLon) {
    foreach ($atrakcje as &$n) {
        $lat = $n['lat'] ?? ($n['center']['lat'] ?? null);
        $lon = $n['lon'] ?? ($n['center']['lon'] ?? null);
        if ($lat && $lon) {
            $n['distance'] = distance($centerLat, $centerLon, $lat, $lon);
        } else {
            $n['distance'] = PHP_INT_MAX;
        }
    }
    unset($n);
    usort($atrakcje, function($a, $b) { return $a['distance'] <=> $b['distance']; });
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Atrakcje w <?= htmlspecialchars($miasto) ?></title>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
<link rel="icon" href="images/favicon.png" type="image/png">
</head>

<?php include 'header.php'; ?>

<body class="page-noclegi">
<div class="container">
    <section class="page-header">   
        <h2>Odkryj ciekawe miejsca.</h2>
    </section>
    
    <h1>Atrakcje w <?= htmlspecialchars($miasto) ?></h1>

    <form method="GET" class="search-form">
      <label for="miasto">Miasto:</label>
      <input type="text" name="miasto" id="miasto" value="<?= htmlspecialchars($miasto) ?>" required>

      <label for="typ">Kategoria:</label>
      <select name="typ" id="typ">
        <option value="museum" <?= $typ=="museum"?"selected":"" ?>>Muzeum</option>
        <option value="viewpoint" <?= $typ=="viewpoint"?"selected":"" ?>>Punkt widokowy</option>
        <option value="zoo" <?= $typ=="zoo"?"selected":"" ?>>ZOO</option>
        <option value="theme_park" <?= $typ=="theme_park"?"selected":"" ?>>Park rozrywki</option>
        <option value="artwork" <?= $typ=="artwork"?"selected":"" ?>>Sztuka / Pomniki</option>
        <option value="attraction" <?= $typ=="attraction"?"selected":"" ?>>Inne atrakcje</option>
      </select>

      <button type="submit">Szukaj</button>
    </form>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <?php 
    if (empty($atrakcje)) {
        echo "<p>Brak wyników dla miasta <strong>" . htmlspecialchars($miasto) . "</strong>.</p>";
    } else {
        $index = 0;
        foreach ($atrakcje as $n): 
            $index++;
            $osmId = $n['id'];
            $osmType = $n['type'];
            
            $nazwa = $n['tags']['name'] ?? 'Bez nazwy';
            $nazwaJS = addslashes($nazwa);

            $lat = $n['lat'] ?? ($n['center']['lat'] ?? null);
            $lon = $n['lon'] ?? ($n['center']['lon'] ?? null);

            $ulica = $n['tags']['addr:street'] ?? '';
            $numer = $n['tags']['addr:housenumber'] ?? '';
            $adres = trim("$ulica $numer");
            if(empty($adres)) $adres = $miasto;

            $gmQuery = urlencode("$nazwa, $adres");
            $googleLink = "https://www.google.com/maps/search/?api=1&query=$gmQuery";
    ?>
        <div class="card">
            <h2><?= $nazwa ?></h2>
            <p class="address"> <?= $adres ?></p>
            <div id="map<?= $index ?>" class="map"></div>
            
            <div style="margin-top: 15px;">
                <a class="btn" href="<?= $googleLink ?>" target="_blank">Google Maps</a>
                <button class="btn-comment" onclick="openComments('<?= $osmId ?>', '<?= $osmType ?>', '<?= htmlspecialchars($nazwaJS, ENT_QUOTES) ?>')">
                Opinie
                </button>
            </div>
        </div>

        <script>
        var map<?= $index ?> = L.map('map<?= $index ?>').setView([<?= $lat ?>, <?= $lon ?>], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors', maxZoom: 19
        }).addTo(map<?= $index ?>);
        L.marker([<?= $lat ?>, <?= $lon ?>]).addTo(map<?= $index ?>).bindPopup(`<?= addslashes($nazwa) ?>`);
        </script>

    <?php 
        endforeach;
    }
    ?>
</div>

<div id="modal-overlay">
    <div id="modal-content">
        <span id="close-modal">×</span>
        <h3 id="modal-title">Opinie</h3>
        <hr>
        <div id="comments-list">Ładowanie...</div>
        <hr>

        <?php if (isset($_SESSION['user_id'])): ?>
            
            <h4>Dodaj opinię jako <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Użytkownik') ?></strong>:</h4>
            <form id="comment-form">
                <input type="hidden" id="c-osm-id">
                <input type="hidden" id="c-osm-type">
                <input type="hidden" id="c-place-name">
                <textarea id="c-text" placeholder="Twoja opinia..." rows="3" required></textarea>
                <button type="submit">Wyślij</button>
            </form>

        <?php else: ?>
            
            <div style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 5px;">
                <p>Musisz być zalogowany, aby dodać opinię.</p>
                <a href="login.php" style="
                    display: inline-block; padding: 10px 20px; 
                    background: #007BFF; color: white; text-decoration: none; 
                    border-radius: 5px; font-weight: bold;">
                    Zaloguj się
                </a>
            </div>

        <?php endif; ?>
    </div>
</div>

<script>

const CURRENT_MODE = 'atrakcje';

const modal = document.getElementById('modal-overlay');
const closeBtn = document.getElementById('close-modal');
const listDiv = document.getElementById('comments-list');


closeBtn.onclick = function() { modal.style.display = "none"; }
window.onclick = function(event) { if (event.target == modal) modal.style.display = "none"; }

function openComments(id, type, name) {
    modal.style.display = "block";
    document.getElementById('modal-title').innerText = "Opinie: " + name;
    
    if(document.getElementById('c-osm-id')) {
        document.getElementById('c-osm-id').value = id;
        document.getElementById('c-osm-type').value = type;
        document.getElementById('c-place-name').value = name;
    }
    loadComments(id, type);
}

function loadComments(id, type) {
    listDiv.innerHTML = "<p>Ładowanie...</p>";
    fetch(`api_comments.php?osm_id=${id}&osm_type=${type}&mode=${CURRENT_MODE}`)
        .then(res => {
            if (!res.ok) { throw new Error("HTTP error " + res.status); }
            return res.text();
        })
        .then(text => {
            try { return JSON.parse(text); } 
            catch (e) { console.error(text); throw new Error("Błąd serwera"); }
        })
        .then(data => {
            if (data.error) { listDiv.innerHTML = `<p style='color:red'>${data.error}</p>`; return; }
            listDiv.innerHTML = "";
            if(!data.length) { listDiv.innerHTML = "<p style='color:gray'>Brak opinii.</p>"; return; }
            
            data.forEach(c => {
                let name = `${c.imie || ''}`.trim();
                listDiv.innerHTML += `
                    <div class="comment-item">
                        <strong>${escapeHtml(name)}</strong> 
                        <span class="comment-meta">(${c.data_dodania})</span>
                        <p>${escapeHtml(c.tresc)}</p>
                    </div>`;
            });
        })
        .catch(err => listDiv.innerHTML = "<p style='color:red'>Błąd ładowania.</p>");
}

const form = document.getElementById('comment-form');
if (form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = form.querySelector('button');
        btn.disabled = true; btn.innerText = "Wysyłanie...";

        const data = {
            mode: CURRENT_MODE,
            osm_id: document.getElementById('c-osm-id').value,
            osm_type: document.getElementById('c-osm-type').value,
            place_name: document.getElementById('c-place-name').value,
            comment_text: document.getElementById('c-text').value
        };

        fetch('api_comments.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            btn.disabled = false; btn.innerText = "Wyślij";
            if(res.success) {
                document.getElementById('c-text').value = '';
                loadComments(data.osm_id, data.osm_type);
            } else {
                alert("Błąd: " + res.error);
            }
        })
        .catch(err => {
            btn.disabled = false;
            alert("Błąd połączenia.");
        });
    });
}

function escapeHtml(text) {
    if (!text) return "";
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
</script>

<?php include 'footer.php'; ?>
</body>
</html>