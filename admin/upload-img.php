<?php include('partials/menu.php'); ?>
<form action="" method="POST" enctype="multipart/form-data">

    <table class="tbl-30">
        <tr>
            <td>Title: </td>
            <td>
                <input type="text" name="title" placeholder="Category Title">
            </td>
        </tr>
        <tr>
            <td>Featured: </td>
            <td>
                <input type="radio" name="featured" value="Yes"> Yes
                <input type="radio" name="featured" value="No"> No
            </td>
        </tr>

        <tr>
            <td>Active: </td>
            <td>
                <input type="radio" name="active" value="Yes"> Yes
                <input type="radio" name="active" value="No"> No
            </td>
        </tr>
        <tr>
            <td>Select Image: </td>
            <td>
                <input type="file" name="image">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="Add Category" class="btn-secondary">
            </td>
        </tr>

    </table>

</form>
<?php
$sql = "SELECT * FROM tbl_category where id = '11'";
$kq = mysqli_query($conn, $sql);
if (mysqli_num_rows($kq) > 0) {
    $row = mysqli_fetch_assoc($kq);
    $img = $row['image_name'];
    if ($img != "") {
        //Display the Image
?>

        <img src="<?php echo SITEURL; ?>images/category/<?php echo $img; ?>" width="100px">

<?php
    } else {
        //DIsplay the MEssage
        echo "<div class='error'>Image not Added.</div>";
    }
}
?>

<?php

//Kiểm tra xem ấn vào nút submit chưa
if (isset($_POST['submit'])) {
    //echo "Clicked";

    //1. nhận giá trị từ form
    $title = $_POST['title'];
    //Kiểm tra xem hình ảnh có được chọn hay không và đặt giá trị cho tên hình ảnh
    //print_r($_FILES['image']);

    //die();//Break the Code Here
    if (isset($_POST['featured'])) {
        //Get the VAlue from form
        $featured = $_POST['featured'];
    } else {
        //SEt the Default VAlue
        $featured = "No";
    }

    if (isset($_POST['active'])) {
        $active = $_POST['active'];
    } else {
        $active = "No";
    }
    if (isset($_FILES['image']['name'])) {
        //Upload the Image
        //Để tải lên hình ảnh, chúng ta cần tên hình ảnh, đường dẫn nguồn và đường dẫn đích
        $image_name = $_FILES['image']['name'];
        echo $image_name;
        // Upload the Image only if image is selected
        if ($image_name != "") {

            //Tự động đổi tên hình ảnh của chúng tôi
            //lấy đuôi của ảnh jpg , png ,....
            $ext = end(explode('.', $image_name));
            // echo $ext;
            //dổi tên ảnh
            $image_name = "Food_Category_" . rand(000, 999) . '.' . $ext; // e.g. Food_Category_834.jpg
            // echo $image_name;

            $source_path = $_FILES['image']['tmp_name'];
            echo $source_path;

            $destination_path = "../images/category/" . $image_name;
            // echo $destination_path;
            //Finally Upload the Image
            $upload = move_uploaded_file($source_path, $destination_path);
            // echo $upload;
            //Check whether the image is uploaded or not
            //And if the image is not uploaded then we will stop the process and redirect with error message
            if ($upload == false) {
                //SEt message
                $_SESSION['upload'] = "<div class='error'>Failed to Upload Image. </div>";
                //Redirect to Add CAtegory Page
                header('location:' . SITEURL . 'admin/add-category.php');
                //STop the Process
                die();
            }
        }
    } else {
        //Don't Upload Image and set the image_name value as blank
        $image_name = "";
    }

    //2. Create SQL Query to Insert CAtegory into Database
    $sql = "INSERT INTO tbl_category SET 
                    title='$title',
                    image_name='$image_name',
                    featured='$featured',
                    active='$active'
                ";
    echo $sql;
    //3. Execute the Query and Save in Database
    $res = mysqli_query($conn, $sql);

    //4. Check whether the query executed or not and data added or not
    if ($res == true) {
        //Query Executed and Category Added
        $_SESSION['add'] = "<div class='success'>Category Added Successfully.</div>";
        //Redirect to Manage Category Page
        echo 'success';
        // header('location:' . SITEURL . 'admin/manage-category.php');
    } else {
        //Failed to Add CAtegory
        $_SESSION['add'] = "<div class='error'>Failed to Add Category.</div>";
        echo 'fail';
        // header('location:' . SITEURL . 'admin/add-category.php');
    }
}

?>