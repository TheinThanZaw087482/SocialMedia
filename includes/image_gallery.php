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

function renderGallery($images, $postId) {
    $totalImages = count($images);
    $hiddenImagesHtml = ''; // ✅ Initialize variable to store hidden images

    if ($totalImages >= 5) {
        echo '<div class="image-gallery five-images"><div class="top-row">';
        for ($i = 0; $i < 2; $i++) {
            echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[$i]['img_url']) . 
                 '" class="post-image" data-post-id="' . $postId . 
                 '" alt="Image" onclick="openModal(this.src, ' . $postId . ')">';
        }

        echo '</div><div class="bottom-row">';
        for ($i = 2; $i < 5; $i++) {
            $overlay = ($i === 4 && $totalImages > 5) 
                ? '<div class="overlay-text">+'.($totalImages - 5).' more</div>' 
                : '';
            echo '<div class="image-overlay">';
            echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[$i]['img_url']) . 
                 '" class="post-image" data-post-id="' . $postId . 
                 '" alt="Image" onclick="openModal(this.src, ' . $postId . ')">';
            echo $overlay;
            echo '</div>';
        }
        echo '</div></div>'; // close .bottom-row and .image-gallery

        // ✅ Add hidden images to DOM inside hidden div
        for ($i = 5; $i < $totalImages; $i++) {
            $hiddenImagesHtml .= '<img src="../assests/images/post_images/' . 
                htmlspecialchars($images[$i]['img_url']) . 
                '" class="post-image" data-post-id="' . $postId . 
                '" alt="Image" onclick="openModal(this.src, ' . $postId . ')">';
        }

        if (!empty($hiddenImagesHtml)) {
            echo '<div style="display:none;">' . $hiddenImagesHtml . '</div>';
        }

    } elseif ($totalImages === 4) {
        echo '<div class="image-gallery four-images"><div class="top-row">';
        for ($i = 0; $i < 2; $i++) {
            echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[$i]['img_url']) . 
                 '" class="post-image" data-post-id="' . $postId . 
                 '" alt="Image" onclick="openModal(this.src, ' . $postId . ')">';
        }
        echo '</div><div class="bottom-row">';
        for ($i = 2; $i < 4; $i++) {
            echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[$i]['img_url']) . 
                 '" class="post-image" data-post-id="' . $postId . 
                 '" alt="Image" onclick="openModal(this.src, ' . $postId . ')">';
        }
        echo '</div></div>';

    } elseif ($totalImages === 3) {
        echo '<div class="image-gallery three-images">';
        echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[0]['img_url']) . 
             '" class="post-image" data-post-id="' . $postId . 
             '" alt="Image" onclick="openModal(this.src, ' . $postId . ')">';
        echo '<div class="bottom-row">';
        echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[1]['img_url']) . 
             '" class="post-image" data-post-id="' . $postId . 
             '" alt="Image" onclick="openModal(this.src, ' . $postId . ')">';
        echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[2]['img_url']) . 
             '" class="post-image" data-post-id="' . $postId . 
             '" alt="Image" onclick="openModal(this.src, ' . $postId . ')">';
        echo '</div></div>';

    } elseif ($totalImages === 2) {
        echo '<div class="image-gallery two-images">';
        for ($i = 0; $i < 2; $i++) {
            echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[$i]['img_url']) . 
                 '" class="post-image" data-post-id="' . $postId . 
                 '" alt="Image" onclick="openModal(this.src, ' . $postId . ')">';
        }
        echo '</div>';

    } elseif ($totalImages === 1) {
        echo '<div class="image-gallery single-image">';
        echo '<img src="../assests/images/post_images/' . htmlspecialchars($images[0]['img_url']) . 
             '" class="post-image" data-post-id="' . $postId . 
             '" alt="Image" onclick="openModal(this.src, ' . $postId . ')">';
        echo '</div>';
    }
}