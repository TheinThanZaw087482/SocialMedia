<?php
function getImagesByPostId($conn, $postId) {
    $stmt = $conn->prepare("SELECT * FROM image WHERE postId = ?");
    if (!$stmt) {
        return []; // SQL error
    }

    $stmt->bind_param("i", $postId);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return []; 
    }
}

function renderGallery($images) {
    $totalImages = count($images);

    if ($totalImages >= 5) {
        echo '<div class="top-row">';
        for ($i = 0; $i < 2; $i++) {
            echo '<div class="image-container">';
            echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[$i]['img_url']) . '" data-id="' . $i . '" onclick="openModal(this)">';
            echo '</div>';
        }
        echo '</div><div class="bottom-row">';
        for ($i = 2; $i < 5; $i++) {
            $isLast = ($i == 4 && $totalImages > 5);
            $extraCount = $totalImages - 5;
            echo '<div class="image-container">';
            echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[$i]['img_url']) . '" data-id="' . $i . '" onclick="openModal(this)">';
            if ($isLast) {
                echo '<div class="overlay" onclick="openModal(this.previousElementSibling)">+'.$extraCount.'</div>';
            }
            echo '</div>';
        }
        echo '</div>';
    } elseif ($totalImages === 4) {
        echo '<div class="gallery four-photos">';
        for ($i = 0; $i < 4; $i++) {
            echo '<div class="image-container">';
            echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[$i]['img_url']) . '" data-id="' . $i . '" onclick="openModal(this)">';
            echo '</div>';
        }
        echo '</div>';
    } elseif ($totalImages === 3) {
        echo '<div class="three-image-layout"><div class="row top">';
        for ($i = 0; $i < 2; $i++) {
            echo '<div class="image-container">';
            echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[$i]['img_url']) . '" data-id="' . $i . '" onclick="openModal(this)">';
            echo '</div>';
        }
        echo '</div><div class="row bottom">';
        echo '<div class="image-container">';
        echo '<img src=" ../assests/images/post_images/' . htmlspecialchars($images[2]['img_url']) . '" data-id="2" onclick="openModal(this)">';
        echo '</div></div></div>';
    } elseif ($totalImages === 2) {
        echo '<div class="two-image-row">';
        for ($i = 0; $i < 2; $i++) {
            echo '<div class="image-container">';
            echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[$i]['img_url']) . '" data-id="' . $i . '" onclick="openModal(this)">';
            echo '</div>';
        }
        echo '</div>';
        
    }
}

function renderModal($images ) {
  if (empty($images)) return; // Optional: skip rendering if empty
  $firstImage = htmlspecialchars($images[0]['img_url']);
  ?>
  <div class="modal" id="imageModal">
    <span class="close-btn" onclick="closeModal()">×</span>
    <div class="modal-content-frame">
      <img id="modalImage" src="../assets/images/post_images/<?= $firstImage ?>" alt="modal image">
      <button onclick="showPrev()" class="prev-btn">‹</button>
      <button onclick="showNext()" class="next-btn">›</button>
    </div>
  </div>


<script>
  const images = [];
  <?php foreach ($images as $img): ?>
    images.push("../assests/images/post_images/<?= htmlspecialchars($img['img_url']) ?>");
  <?php endforeach; ?>
</script>

<script src="../assests/js/img_modal.js"></script>

<?php
} ?>