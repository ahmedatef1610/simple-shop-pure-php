<?php
ob_start();
session_start();
$pageTitle = 'Create New Item' ;
include 'init.php';
if ( isset( $_SESSION['user'] ) ) {
    if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

        $formErrors = [];

        $name 		 = filter_var( $_POST['name'], FILTER_SANITIZE_STRING );
        $desc 		 = filter_var( $_POST['description'], FILTER_SANITIZE_STRING );
        $price 		 = filter_var( $_POST['price'], FILTER_SANITIZE_NUMBER_INT );
        $country 	 = filter_var( $_POST['country'], FILTER_SANITIZE_STRING );
        $status 	 = filter_var( $_POST['status'], FILTER_SANITIZE_NUMBER_INT );
        $rating 	 = filter_var( $_POST['rating'], FILTER_SANITIZE_NUMBER_INT );
        $category 	 = filter_var( $_POST['category'], FILTER_SANITIZE_NUMBER_INT );
        $tags 	 = filter_var( $_POST['tags'], FILTER_SANITIZE_STRING );

        if ( strlen( $name ) < 2 ) {
            $formErrors[] = 'Item Title Must Be At Least 4 Characters';
        }
        if ( strlen( $desc ) < 10 ) {
            $formErrors[] = 'Item Description Must Be At Least 10 Characters';
        }
        if ( strlen( $country ) < 2 ) {
            $formErrors[] = 'Item Title Must Be At Least 2 Characters';
        }
        if ( empty( $price ) ) {
            $formErrors[] = 'Item Price Cant Be Empty';
        }
        if ( empty( $status ) ) {
            $formErrors[] = 'Item Status Cant Be Empty';
        }
        if ( empty( $rating ) ) {
            $formErrors[] = 'Item rating Cant Be Empty';
        }
        if ( empty( $category ) ) {
            $formErrors[] = 'Item Category Cant Be Empty';
        }

        // Check If There's No Error Proceed The Update Operation

        if (empty($formErrors)) {
            // Insert User info In Database
            $stmt = $con->prepare("INSERT INTO 
                items(Name, Description, Price, CountryMade, Status, AddDate, CatId, MemberId, Rating,Tags)
                VALUES(:name, :desc, :price, :country, :status, now(), :category, :member, :rating,:tags)");

            $stmt->execute(array(
                'name' 	    => $name,
                'desc' 	    => $desc,
                'price' 	=> $price,
                'country' 	=> $country,
                'status' 	=> $status,
                'category'	=> $category,
                'member'	=> $_SESSION['userId'],
                'rating'	=> $rating,
                'tags'	=> $tags
            ));

            // Echo Success Message
            if ($stmt) {
                $successMsg = 'Item Has Been Added'; 
            }
        }
    }
?>

<h1 class='text-center display-4 my-3'>Create New Item <i class='fad fa-ad'></i></h1>
<div class='information my-3'>
    <div class='container'>
        <div class='card'>
            <div class='card-header'>
                Create New Ad
            </div>
            <div class='card-body'>
                <div class='row'>
                    <div class="col-md-8 mb-3">
                        <form class='form-horizontal' action='<?php echo $_SERVER['PHP_SELF'] ?>' method='post'>

                            <div class='form-group row'>
                                <label for='name' class='col-sm-2 col-form-label'>Name</label>
                                <div class='col-sm-10'>
                                    <input type='text' class='form-control live' id='name' name='name'
                                        data-class=".live-title" pattern=".{2,}"
                                        title="This Field Must Be Between 4 Chars" required>
                                </div>
                            </div>

                            <div class='form-group row'>
                                <label for='description' class='col-sm-2 col-form-label'>Description</label>
                                <div class='col-sm-10'>
                                    <input type='text' class='form-control live' id='description' name='description'
                                        data-class=".live-description" required>
                                </div>
                            </div>

                            <div class='form-group row'>
                                <label for='price' class='col-sm-2 col-form-label'>Price</label>
                                <div class='col-sm-10'>
                                    <input type='number' class='form-control live' id='price' name='price'
                                        data-class=".live-price" required>
                                </div>
                            </div>

                            <div class='form-group row'>
                                <label for='country' class='col-sm-2 col-form-label'>Country of Made</label>
                                <div class='col-sm-10'>
                                    <input type='text' class='form-control live' id='country' name='country'
                                        data-class=".live-country" required>
                                </div>
                            </div>

                            <div class='form-group row'>
                                <label for='status' class='col-sm-2 col-form-label'>Status</label>
                                <div class='col-sm-10'>
                                    <select class="form-control" id='status' name='status' required>
                                        <option value="" hidden>Choose Status from here</option>
                                        <option value="1">New</option>
                                        <option value="2">Like New</option>
                                        <option value="3">Old</option>
                                        <option value="4">Very Old</option>
                                        <option value="5">Used</option>
                                    </select>
                                </div>
                            </div>

                            <div class='form-group row'>
                                <label for='rating' class='col-sm-2 col-form-label'>Rating</label>
                                <div class='col-sm-10'>
                                    <select class="form-control" id='rating' name='rating' required>
                                        <option value="" hidden>Choose Rating from here</option>
                                        <option value="0">💩</option>
                                        <option value="1">⭐</option>
                                        <option value="2">⭐⭐</option>
                                        <option value="3">⭐⭐⭐</option>
                                        <option value="4">⭐⭐⭐⭐</option>
                                        <option value="5">⭐⭐⭐⭐⭐</option>
                                    </select>
                                </div>
                            </div>

                            <div class='form-group row'>
                                <label for='category' class='col-sm-2 col-form-label'>Category</label>
                                <div class='col-sm-10'>
                                    <select class="form-control" id='category' name='category' required>
                                        <option value="" hidden>Choose Category from here</option>
                                        <?php
                                        $allCats = getAllFrom("*", "categories", "", "", "Id");
                                        foreach ($allCats as $cat) {
                                            echo "<option value='" . $cat['Id'] . "'>" . $cat['Name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class='form-group row'>
                                <label for='tags' class='col-sm-2 col-form-label'>Tags</label>
                                <div class='col-sm-10'>
                                    <input type='text' class='form-control' id='tags' name='tags'
                                        placeholder="Separate Tags With Comma ( , )" required>
                                </div>
                            </div>

                            <div class='form-group row'>
                                <div class='col-sm-10 offset-sm-2'>
                                    <button type='submit' class='btn btn-primary'><i
                                            class="fad fa-dolly-flatbed-alt"></i> Add Item</button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="card live-preview" style="height: 100%;">
                            <img src="<?php echo $img ?>p.jpeg" class="card-img-top" alt="img">
                            <div class="card-body d-flex flex-column justify-content-end">
                                <h3 class="card-title live-title">title</h3>
                                <p class="card-text live-description">description</p>
                                <p class="card-text item-box">
                                    <span class="badge badge-primary mr-1 "><span class="live-price">0</span> $</span>
                                    <span class="badge badge-primary mr-1 live-country">country</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Start Looping Through Errors -->
                <?php 
					if (! empty($formErrors)) {
						foreach ($formErrors as $error) {
							echo '<div class="alert alert-danger">' . $error . '</div>';
						}
					}
					if (isset($successMsg)) {
						echo '<div class="alert alert-success">' . $successMsg . '</div>';
					}
				?>
                <!-- End Looping Through Errors -->
            </div>
        </div>
    </div>
</div>

<?php
} else {
    header( 'Location: login.php' );
    exit();
}
include $tpl.'footer.php';
        ob_end_flush();
?>