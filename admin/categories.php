<?php include "includes/admin_header.php"; ?>

    <div id="wrapper">

    <!-- Navigation -->
    <?php include "includes/admin_navigation.php"; ?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">


                        <h1 class="page-header">
                            All Categories
                            <small>Author</small>
                        </h1>
                        
                        <div class="col-xs-6">


                            <?php insert_categories(); ?>

                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="cat_title">Add Category</label>
                                    <input class="form-control" type="text" name="cat_title">
                                </div>
                                <div class="form-group">
                                    <input class="btn btn-primary" type="submit" name="submit" value="Add Category">
                                </div>
                            </form>
                            
                            <?php // UPDATE AND INCLUDE QUERY

                            if ($_SESSION['user_role'] == 'admin') {
                                
                                if(isset($_GET['edit'])) { // 'edit' _GET request from findAllCategories(); 
                                    $cat_id = escape($_GET['edit']);

                                    include "includes/update_categories.php";

                                }
                            }

                            ?>

                        </div> <!-- Add Category Form -->                      
                        
                        <div class="col-xs-6"> 


                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Category Title</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                <?php findAllCategories(); ?>

                                <?php deleteCategories(); ?>
                                    
                                </tbody>
                            </table>
                        </div> <!-- Add Table -->


                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<?php include "includes/admin_footer.php"; ?>
