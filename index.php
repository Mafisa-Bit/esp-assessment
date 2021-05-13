<!DOCTYPE html>
<html>
<body>

<form method="post" enctype="multipart/form-data">
    <label>
        Choose Entity
        <select name="entity">
            <option value="staff">
                Staff
            </option>
            <option value="product">
                Product
            </option>
        </select>

    </label>

    <br>
    <br>
    Select image to upload:
    <input type="file" name="image">
    <input type="submit" value="submit">
    <br>
    <br>
</form>
<hr>
<ul>
    <li>Sent file: <?php echo $_FILES['image']['name']; ?>
    <li>File size: <?php echo $_FILES['image']['size']; ?>
    <li>File type: <?php echo $_FILES['image']['type'] ?>
</ul>
</body>
</html>
<?php
include 'upload.php'
?>