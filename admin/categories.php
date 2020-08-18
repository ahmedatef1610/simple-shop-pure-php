<?php
ob_start();
// Output Buffering Start

session_start();
$pageTitle = 'Categories';

if ( isset( $_SESSION['UserName'] ) ) {
    include 'init.php';

    $do = isset( $_GET['do'] ) ? $_GET['do'] : 'manage';

    if ( $do == 'manage' ) {
        // 11111
        $sort = '';
        $query = '';
        $sort_array = ['asc', 'desc'];
        if ( isset( $_GET['sort'] ) && in_array( $_GET['sort'], $sort_array ) ) {
            $sort = $_GET['sort'];
            $query = 'ORDER BY Ordering';
        }
        // Select All Users Except Admin
        $stmt = $con->prepare( "SELECT * FROM categories WHERE parent=0 $query $sort" );
        // Execute The Statement
        $stmt->execute();
        // Assign To Variable
        $rows = $stmt->fetchAll();
        if ( ! empty( $rows ) ) {
            ?>
<h1 class='text-center display-4 mt-3'>Manage Categories</h1>
<div class='categories'>
    <div class='container my-3'>
        <a class='btn btn-primary my-3' href='categories.php?do=add'><i class='fa fa-plus'></i> New Categories</a>

        <div class='card'>
            <div class='card-header'>
                <i class='fa fa-layer-group'></i> Manage Categories
                <div class='option float-right'>
                    <i class='fa fa-sort'></i> Ordering: [
                    <a class="<?php if ($sort == 'asc') { echo 'active'; } ?>" href='?sort=asc'>Asc</a> |
                    <a class="<?php if ($sort == 'desc') { echo 'active'; } ?>" href='?sort=desc'>Desc</a> ]
                    <i class='fa fa-eye'></i> View: [
                    <span class='active' data-view='full'>Full</span> |
                    <span data-view='classic'>Classic</span> ]
                </div>
            </div>
            <div class='card-body p-0'>
                <?php
                    foreach ( $rows as $row ) {
                        echo "<div class='cat'>";
                            echo "<div class='hidden-buttons'>";
                                echo "<a href='categories.php?do=edit&CatId=" . $row['Id'] . "' class='btn btn-sm btn-primary'><i class='fa fa-edit '></i> Edit</a>";
                                echo "<a href='categories.php?do=delete&CatId=" . $row['Id'] . "' class='confirm btn btn-sm btn-danger'><i class='fa fa-times'></i> Delete</a>";
                            echo '</div>';
                            echo "<h5 class='card-title text-success'>".$row['Name'].'</h5>';
                            echo "<div class='full-view'>";
                                echo "<p class='card-text'>";
                                echo ( $row['Description'] == '' )?'This category has no description':$row['Description'];
                                echo'</p>';
                                echo ( $row['Visibility'] == '1' )?"<span class='badge badge-primary mr-3'><i class='fa fa-eye'></i> Hidden</span>":'';
                                echo ( $row['AllowComment'] == '1' )?"<span class='badge badge-danger mr-3'><i class='fa fa-times'></i> Comment Disabled</span>":'';
                                echo ( $row['AllowAds'] == '1' )?"<span class='badge badge-success mr-3'><i class='fa fa-times'></i> Ads Disabled</span>":'';
                            echo '</div>';
                            $childCats = getAllFrom("*", "categories", "where parent = {$row['Id']}", "", "Id", "ASC");
                            if (! empty($childCats)) {
                                echo "<h5 class='child-head mt-3 text-info text-center'>Child Categories</h5>";
                                echo "<ul class='list-group list-group-flush child-cats'>";
                                foreach ($childCats as $c) {
                                    echo "<li class='list-group-item child-link'>
                                        <a href='categories.php?do=edit&CatId=" . $c['Id'] . "'>" . $c['Name'] . "</a>
                                        <a href='categories.php?do=delete&CatId=" . $c['Id'] . "' class='confirm text-danger float-right'> Delete</a>
                                    </li>";
                               }
                                echo "</ul>";
                            }
                        echo '</div>';
                        echo "<div class='dropdown-divider'></div>";
                    }
                    ?>
            </div>
        </div>
    </div>
</div>

<?php
                } else {
                    echo '<div class="container py-3">';
                    echo '<div class="alert alert-info">There\'s No Categories To Show</div>';
            echo "<a href = 'categories.php?do=add' class = 'btn btn-primary'><i class = 'fa fa-plus'></i> New categories</a>";
            echo '</div>';
        }
        
    } elseif ( $do == 'add' ) {
        // 22222
        ?>

<h1 class='text-center display-4 mt-3'>Add Category</h1>
<div class='container px-5'>
    <form class='form-horizontal' action='?do=insert' method='post'>

        <div class='form-group row'>
            <label for='name' class='col-sm-2 col-form-label'>Name</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' id='name' name='name' required>
            </div>
        </div>

        <div class='form-group row'>
            <label for='description' class='col-sm-2 col-form-label'>Description</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' id='description' name='description'>
            </div>
        </div>

        <div class='form-group row'>
            <label for='ordering' class='col-sm-2 col-form-label'>Ordering</label>
            <div class='col-sm-10'>
                <input type='number' class='form-control' id='ordering' name='ordering'>
            </div>
        </div>

        <div class='form-group row'>
            <label for='parent' class='col-sm-2 col-form-label'>Parent Of</label>
            <div class='col-sm-10'>
                <select class="form-control" id='parent' name='parent' required>
                    <option value="" hidden>Choose Parent Category from here</option>
                    <option value="0">None</option>
                    <?php
                        $allCats = getAllFrom("*", "categories", "WHERE parent = 0", "", "Id","ASC");
                        foreach ($allCats as $cat) {
                            echo "<option value='" . $cat['Id'] . "'>" . $cat['Name'] . "</option>";
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class='form-group'>
            <div class='row'>
                <label class='col-form-label col-sm-2 pt-0'>Visible</label>
                <div class='col-sm-10'>
                    <div class='form-check'>
                        <input class='form-check-input' type='radio' name='visibility' id='vis-yes' value='0' checked>
                        <label class='form-check-label' for='vis-yes'>Yes</label>
                    </div>
                    <div class='form-check'>
                        <input class='form-check-input' type='radio' name='visibility' id='vis-no' value='1'>
                        <label class='form-check-label' for='vis-no'>No</label>
                    </div>
                </div>
            </div>
        </div>

        <div class='form-group'>
            <div class='row'>
                <label class='col-form-label col-sm-2 pt-0'>Allow Commenting</label>
                <div class='col-sm-10'>
                    <div class='form-check'>
                        <input class='form-check-input' type='radio' name='commenting' id='com-yes' value='0' checked>
                        <label class='form-check-label' for='com-yes'>Yes</label>
                    </div>
                    <div class='form-check'>
                        <input class='form-check-input' type='radio' name='commenting' id='com-no' value='1'>
                        <label class='form-check-label' for='com-no'>No</label>
                    </div>
                </div>
            </div>
        </div>

        <div class='form-group'>
            <div class='row'>
                <label class='col-form-label col-sm-2 pt-0'>Allow Ads</label>
                <div class='col-sm-10'>
                    <div class='form-check'>
                        <input class='form-check-input' type='radio' name='ads' id='com-yes' value='0' checked>
                        <label class='form-check-label' for='com-yes'>Yes</label>
                    </div>
                    <div class='form-check'>
                        <input class='form-check-input' type='radio' name='ads' id='com-no' value='1'>
                        <label class='form-check-label' for='com-no'>No</label>
                    </div>
                </div>
            </div>
        </div>

        <div class='form-group row'>
            <div class='col-sm-10 offset-sm-2'>
                <button type='submit' class='btn btn-primary'><i class='fad fa-layer-group'></i> Add Category</button>
            </div>
        </div>

    </form>
</div>

<?php
    } elseif ( $do == 'insert' ) {
        // 33333
        echo "<h1 class='text-center display-4 mt-3'>Insert Member</h1>";
        echo "<div class='container my-3'>";

        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

            $name = $_POST['name'];
            $description = $_POST['description'];
            $ordering = $_POST['ordering'];
            $parent = $_POST['parent'];
            $visibility = $_POST['visibility'];
            $commenting = $_POST['commenting'];
            $ads = $_POST['ads'];
            // Check If Category Exist in Database
            $check = checkItem( 'Name', 'categories', $name );
            if ( $check >= 1 ) {
                redirectHome( "<div class = 'alert alert-danger'>Sorry This Category Is Exist</div>", 'back' );
            } elseif(empty($name)){
                redirectHome( "<div class = 'alert alert-danger'>Sorry This Category Name not Found</div>", 'back' );
            }else {
                // Insert Category In Database
                $stmt = $con->prepare("INSERT INTO categories(Name,Description,Ordering,Visibility,AllowComment,AllowAds,Parent)
                                       VALUES(:name,:description,:ordering,:visibility,:commenting,:ads,:parent) ");
                $stmt->execute(['name' => $name, 
                                'description' => $description, 
                                'ordering' => $ordering, 
                                'visibility' => $visibility,
                                'commenting' => $commenting,
                                'ads' => $ads,
                                'parent' => $parent,
                                ]);
                // Echo Success Message
                redirectHome( "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div>' ,'back');
            }
        } else {
            redirectHome( "<div class = 'alert alert-danger'>Sorry You Cant Browse This Page Directly</div>", 'back' );
        }

        echo '</div>';
        ?>
<?php
    } elseif ( $do == 'edit' ) {
                    // 44444
                    // Check If Get Request UserId Is Numeric & Get Its Integer Value
                    $CatId = isset( $_GET['CatId'] ) && is_numeric( $_GET['CatId'] ) ? intval( $_GET['CatId'] ) : 0;
                    // Select All Data Depend On This ID
                    $stmt = $con->prepare( 'SELECT * FROM categories WHERE Id = ? LIMIT 1' );
                    // Execute Query
                    $stmt->execute( [$CatId] );
                    // Fetch The Data
                    $row = $stmt->fetch();
                    // The Row Count
                    $count = $stmt->rowCount();
                    // If There's Such ID Show The Form
                    if ( $count > 0 ) {
                        ?>

<h1 class='text-center display-4 mt-3'>Edit Category</h1>
<div class='container py-5'>
    <form class='form-horizontal' action='?do=update' method='post'>

        <input type='hidden' name='CatId' value="<?php echo $CatId ?>">

        <div class='form-group row'>
            <label for='name' class='col-sm-2 col-form-label'>Name</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' id='name' name='name' value="<?php echo $row['Name'] ?>"
                    required>
            </div>
        </div>

        <div class='form-group row'>
            <label for='description' class='col-sm-2 col-form-label'>Description</label>
            <div class='col-sm-10'>
                <input type='text' class='form-control' id='description' name='description'
                    value="<?php echo $row['Description'] ?>">
            </div>
        </div>

        <div class='form-group row'>
            <label for='ordering' class='col-sm-2 col-form-label'>Ordering</label>
            <div class='col-sm-10'>
                <input type='number' class='form-control' id='ordering' name='ordering'
                    value="<?php echo $row['Ordering'] ?>">
            </div>
        </div>

        <div class='form-group row'>
            <label for='parent' class='col-sm-2 col-form-label'>Parent Of</label>
            <div class='col-sm-10'>
                <select class="form-control" id='parent' name='parent' required>
                    <option value="" hidden>Choose Parent Category from here</option>
                    <option value="0">None</option>
                    <?php
                        $allCats = getAllFrom("*", "categories", "WHERE parent = 0", "", "Id","ASC");
                        foreach ($allCats as $cat) {
                            echo "<option value='" . $cat['Id'] . "'";
                            if ( $cat['Id'] == $row['Parent'] ) {
                                echo 'selected';
                            }
                            echo '>' . $cat['Name'] . '</option>';
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class='form-group'>
            <div class='row'>
                <label class='col-form-label col-sm-2 pt-0'>Visible</label>
                <div class='col-sm-10'>
                    <div class='form-check'>
                        <input class='form-check-input' type='radio' name='visibility' id='vis-yes' value='0'
                            <?php if ($row['Visibility'] == 0) { echo 'checked'; } ?>>
                        <label class='form-check-label' for='vis-yes'>Yes</label>
                    </div>
                    <div class='form-check'>
                        <input class='form-check-input' type='radio' name='visibility' id='vis-no' value='1'
                            <?php if ($row['Visibility'] == 1) { echo 'checked'; } ?>>
                        <label class='form-check-label' for='vis-no'>No</label>
                    </div>
                </div>
            </div>
        </div>

        <div class='form-group'>
            <div class='row'>
                <label class='col-form-label col-sm-2 pt-0'>Allow Commenting</label>
                <div class='col-sm-10'>
                    <div class='form-check'>
                        <input class='form-check-input' type='radio' name='commenting' id='com-yes' value='0'
                            <?php if ($row['AllowComment'] == 0) { echo 'checked'; } ?>>
                        <label class='form-check-label' for='com-yes'>Yes</label>
                    </div>
                    <div class='form-check'>
                        <input class='form-check-input' type='radio' name='commenting' id='com-no' value='1'
                            <?php if ($row['AllowComment'] == 1) { echo 'checked'; } ?>>
                        <label class='form-check-label' for='com-no'>No</label>
                    </div>
                </div>
            </div>
        </div>

        <div class='form-group'>
            <div class='row'>
                <label class='col-form-label col-sm-2 pt-0'>Allow Ads</label>
                <div class='col-sm-10'>
                    <div class='form-check'>
                        <input class='form-check-input' type='radio' name='ads' id='com-yes' value='0'
                            <?php if ($row['AllowAds'] == 0) { echo 'checked'; } ?>>
                        <label class='form-check-label' for='com-yes'>Yes</label>
                    </div>
                    <div class='form-check'>
                        <input class='form-check-input' type='radio' name='ads' id='com-no' value='1'
                            <?php if ($row['AllowAds'] == 1) { echo 'checked'; } ?>>
                        <label class='form-check-label' for='com-no'>No</label>
                    </div>
                </div>
            </div>
        </div>

        <div class='form-group row'>
            <div class='col-sm-10 offset-sm-2'>
                <button type='submit' class='btn btn-primary'><i class='fad fa-layer-group'></i> Save Category</button>
            </div>
        </div>

    </form>
</div>
<?php
        } else {
            echo "<div class='container my-3'>";
            redirectHome( "<div class = 'alert alert-danger'>Theres No Such ID</div>" ,"back");
            echo '</div>';
        }
            } elseif ( $do == 'update' ) {
                // 55555
                echo "<h1 class='text-center display-4 mt-3'>Update Category</h1>";
                if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
                    $CatId 	 = $_POST['CatId'];
                    $name = $_POST['name'];
                    $description = $_POST['description'];
                    $ordering = $_POST['ordering'];
                    $visibility = $_POST['visibility'];
                    $commenting = $_POST['commenting'];
                    $ads = $_POST['ads'];
                    $parent = $_POST['parent'];

                    // Check If There's No Error Proceed The Update Operation
                if (! empty( $name ) ) {
                    // Update The Database With This Info
                    $stmt = $con->prepare( 'UPDATE categories SET Name = ?, Description = ?, Ordering = ?, Visibility = ?,AllowComment=?,AllowAds=?,Parent=? WHERE Id = ?' );
                    $stmt->execute( [$name, $description, $ordering, $visibility,$commenting,$ads,$parent,$CatId ] );
                    echo "<div class='container my-3'>";
                    redirectHome("<div class='alert alert-success'>". $stmt->rowCount() ." Record Updated</div>",1);
                    echo '</div>';
                }
            } else {
                echo "<div class='container my-3'>";
                redirectHome("<div class = 'alert alert-danger'>Sorry You Cant Browse This Page Directly</div>");
                echo '</div>';
            }

                
            } elseif ( $do == 'delete' ) {
                // 66666
                // Delete Category Page
                echo "<h1 class='text-center display-4 mt-3'>Delete Category</h1>";
                echo "<div class='container my-3'>";
                // Check If Get Request CatId Is Numeric & Get The Integer Value Of It
                $CatId = isset( $_GET['CatId'] ) && is_numeric( $_GET['CatId'] ) ? intval( $_GET['CatId'] ) : 0;
                // Select All Data Depend On This ID
                $check = checkItem('Id', 'categories', $CatId);
                if ( $check > 0 ) {
                // If There's Such ID Show The Form
                $stmt = $con->prepare( 'DELETE FROM categories WHERE Id = :CatId' );
                $stmt->bindParam( ':CatId', $CatId );
                $stmt->execute();
                redirectHome("<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div>' ,"back");
                } else {
                    redirectHome("<div class = 'alert alert-danger'>This ID is Not Exist</div>","back");
                }
                echo '</div>';
            }

        include $tpl . 'footer.php';
    } else {
        header( 'Location: index.php' );
        exit();
    }
    ob_end_flush();
    // Release The Output

?>