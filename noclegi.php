<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$miasto = $_GET['miasto'] ?? 'Katowice';
$typ = $_GET['typ'] ?? 'hotel';
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
$noclegi = $data['elements'] ?? [];


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

$centerLat = null;
$centerLon = null;
if (!empty($noclegi[0]['lat'])) {
    $centerLat = $noclegi[0]['lat'];
    $centerLon = $noclegi[0]['lon'];
} elseif (!empty($noclegi[0]['center'])) {
    $centerLat = $noclegi[0]['center']['lat'];
    $centerLon = $noclegi[0]['center']['lon'];
}

if ($centerLat && $centerLon) {
    foreach ($noclegi as &$n) {
        $lat = $n['lat'] ?? ($n['center']['lat'] ?? null);
        $lon = $n['lon'] ?? ($n['center']['lon'] ?? null);
        if ($lat && $lon) {
            $n['distance'] = distance($centerLat, $centerLon, $lat, $lon);
        } else {
            $n['distance'] = PHP_INT_MAX;
        }
    }
    unset($n);
    usort($noclegi, function($a, $b) {
        return $a['distance'] <=> $b['distance'];
    });
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Noclegi w <?= htmlspecialchars($miasto) ?></title>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="styles.css">
<link rel="icon" href="images/favicon.png" type="image/png">
</head>

<?php include 'header.php'; ?>

<body class="page-noclegi">
<div class="container">
    <section class="page-header">   
        <h2>Znajdź najlepsze miejsca na nocleg.</h2>
    </section>
    
    <h1>Noclegi w <?= htmlspecialchars($miasto) ?></h1>

    <form method="GET" class="search-form">
      <label for="miasto">Miasto:</label>
      <input type="text" name="miasto" id="miasto" value="<?= htmlspecialchars($miasto) ?>" required>

      <label for="typ">Rodzaj noclegu:</label>
      <select name="typ" id="typ">
        <option value="hotel" <?= $typ=="hotel"?"selected":"" ?>>Hotel</option>
        <option value="hostel" <?= $typ=="hostel"?"selected":"" ?>>Hostel</option>
        <option value="motel" <?= $typ=="motel"?"selected":"" ?>>Motel</option>
      </select>

      <button type="submit">Szukaj</button>
    </form>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <?php 
    if (empty($noclegi)) {
        echo "<p>Brak wyników dla miasta <strong>" . htmlspecialchars($miasto) . "</strong> i typu <strong>" . htmlspecialchars($typ) . "</strong>.</p>";
    } else {
        $index = 0;
        foreach ($noclegi as $n): 
            $index++;

            $osmId = $n['id'];
            $osmType = $n['type'];
            
            $nazwa = $n['tags']['name'] ?? 'Bez nazwy';
            $nazwaJS = addslashes($nazwa);

            $lat = $n['lat'] ?? ($n['center']['lat'] ?? null);
            $lon = $n['lon'] ?? ($n['center']['lon'] ?? null);

            $ulica = $n['tags']['addr:street'] ?? '';
            $numer = $n['tags']['addr:housenumber'] ?? '';
            $kod = $n['tags']['addr:postcode'] ?? '';

            $adres = trim("$ulica $numer, $kod $miasto");
            if (empty($ulica) && empty($numer) && empty($kod)) {
                $adres = $miasto;
            }

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
        var map<?= $index ?> = L.map('map<?= $index ?>').setView([<?= $lat ?>, <?= $lon ?>], 16.5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map<?= $index ?>);

        L.marker([<?= $lat ?>, <?= $lon ?>]).addTo(map<?= $index ?>)
            .bindPopup(`<?= addslashes($nazwa) ?>`);
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
        
        <div id="comments-list">
            Ładowanie...
        </div>
        <hr>

        <?php if (isset($_SESSION['user_id'])): ?>
            
            <h4>Dodaj opinię jako <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Użytkownik') ?></strong>:</h4>
            
            <form id="comment-form">
                <input type="hidden" id="c-osm-id">
                <input type="hidden" id="c-osm-type">
                <input type="hidden" id="c-place-name">
                
                <textarea id="c-text" placeholder="Napisz swoją opinię..." rows="3" required></textarea>
                <button type="submit">Wyślij</button>
            </form>

        <?php else: ?>
            
            <div style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 5px;">
                <p>Musisz być zalogowany, aby dodać opinię.</p>
                <a href="login.php" style="
                    display: inline-block; 
                    padding: 10px 20px; 
                    background: #007BFF; 
                    color: white; 
                    text-decoration: none; 
                    border-radius: 5px;
                    font-weight: bold;">
                    Zaloguj się
                </a>
            </div>

        <?php endif; ?>
    </div>
</div>

<script>
const CURRENT_MODE = 'noclegi';
const modal = document.getElementById('modal-overlay');
const closeBtn = document.getElementById('close-modal');
const listDiv = document.getElementById('comments-list');


closeBtn.onclick = function() { modal.style.display = "none"; }
window.onclick = function(event) {
    if (event.target == modal) modal.style.display = "none";
}

function openComments(id, type, name) {
    modal.style.display = "block";
    document.getElementById('modal-title').innerText = "Opinie: " + name;
    const inputId = document.getElementById('c-osm-id');
    if (inputId) {
        document.getElementById('c-osm-id').value = id;
        document.getElementById('c-osm-type').value = type;
        document.getElementById('c-place-name').value = name;
    }
    
    loadComments(id, type);
}


function loadComments(id, type) {
    listDiv.innerHTML = "<p>Ładowanie opinii...</p>";
    fetch(`api_comments.php?osm_id=${id}&osm_type=${type}&mode=${CURRENT_MODE}`)
        .then(res => {
            
            if (!res.ok) { throw new Error("HTTP error " + res.status); }
            return res.text();
        })
        .then(text => {
            try {
                return JSON.parse(text); 
            } catch (e) {
                console.error("Server returned non-JSON:", text);
                throw new Error("Błąd serwera (zobacz konsolę)");
            }
        })
        .then(data => {
            if (data.error) {
                listDiv.innerHTML = `<p style='color:red'>Błąd: ${data.error}</p>`;
                return;
            }
            
            listDiv.innerHTML = "";
            if(Array.isArray(data) && data.length === 0) {
                listDiv.innerHTML = "<p style='color:gray'>Brak opinii. Bądź pierwszy!</p>";
                return;
            }
            
            data.forEach(c => {
                let userName = `${c.imie}`.trim();
                if (!userName) userName = "Anonim";
                let html = `
                    <div class="comment-item">
                        <strong>${escapeHtml(userName)}</strong> 
                        <span class="comment-meta">(${c.data_dodania})</span>
                        <p>${escapeHtml(c.tresc)}</p>
                    </div>`;
                listDiv.innerHTML += html;
            });
        })
        .catch(err => {
            console.error(err);
            listDiv.innerHTML = `<p style='color:red'>${err.message}</p>`;
        });
}


const form = document.getElementById('comment-form');
if (form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = form.querySelector('button');
        submitBtn.disabled = true; 
        submitBtn.innerText = "Wysyłanie...";

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
            submitBtn.disabled = false;
            submitBtn.innerText = "Wyślij";

            if(res.success) {
                document.getElementById('c-text').value = ''; 
                loadComments(data.osm_id, data.osm_type);
            } else {
                alert("Błąd: " + res.error);
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            alert("Wystąpił błąd połączenia.");
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