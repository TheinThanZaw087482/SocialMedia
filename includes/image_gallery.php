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

    if ($totalImages >= 6) {
        echo '<div class="image-gallery six-images"><div class="top-row">';
        for ($i = 0; $i < 2; $i++) {
            echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[$i]['img_url']) . '" alt="Image">';
        }
        echo '</div><div class="bottom-row">';
        for ($i = 2; $i < 6; $i++) {
            $overlay = ($i === 5 && $totalImages > 6) ? '<div class="overlay-text">+'.($totalImages - 6).' more</div>' : '';
            echo '<div class="image-overlay">';
            echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[$i]['img_url']) . '" alt="Image">';
            echo $overlay;
            echo '</div>';
        }
        echo '</div></div>';
    } elseif ($totalImages === 5) {
        echo '<div class="image-gallery five-images"><div class="top-row">';
        for ($i = 0; $i < 2; $i++) {
            echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[$i]['img_url']) . '" alt="Image">';
        }
        echo '</div><div class="bottom-row">';
        for ($i = 2; $i < 5; $i++) {
            echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[$i]['img_url']) . '" alt="Image">';
        }
        echo '</div></div>';
    } elseif ($totalImages === 4) {
        echo '<div class="image-gallery four-images"><div class="top-row">';
        for ($i = 0; $i < 2; $i++) {
            echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[$i]['img_url']) . '" alt="Image">';
        }
        echo '</div><div class="bottom-row">';
        for ($i = 2; $i < 4; $i++) {
            echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[$i]['img_url']) . '" alt="Image">';
        }
        echo '</div></div>';
    } elseif ($totalImages === 3) {
        echo '<div class="image-gallery three-images">';
        echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[0]['img_url']) . '" alt="Image">';
        echo '<div class="bottom-row">';
        echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[1]['img_url']) . '" alt="Image">';
        echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[2]['img_url']) . '" alt="Image">';
        echo '</div></div>';
    } elseif ($totalImages === 2) {
        echo '<div class="image-gallery two-images">';
        for ($i = 0; $i < 2; $i++) {
            echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[$i]['img_url']) . '" alt="Image">';
        }
        echo '</div>';
    } elseif ($totalImages === 1) {
        echo '<div class="image-gallery single-image">';
        echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[0]['img_url']) . '" alt="Single Post Image">';
        echo '</div>';
    }
}
