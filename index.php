<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Video Upload</title>
    </head>

    <body>
        <div class="container">
            <h2>Upload Video</h2>
            <form method="POST" id="uploadForm" enctype="multipart/form-data" action="backend/api/upload.php">
                <input type="text" name="title" placeholder="video title" required>
                <textarea name="description" placeholder="Video description" id=""></textarea>
                <input type="file" name="video" accept="video/*">
                <button type="submit">Upload Video</button>
            </form>
        </div>
        <!-- <script src="js/upload.js"></script> -->
    </body>

</html>