<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="save" method="post" enctype="multipart/form-data">
        <input type="number" name="year" id="year" min="1900" value="2023" max="2099">
        <input type="file" name="image_1">

        <div><?= $errors['image_1'] ?? '' ?></div>
        <input type="file" name="image_2">
        <input type="file" name="image_3">
        <button type="submit">Save</button>
    </form>
</body>

</html>