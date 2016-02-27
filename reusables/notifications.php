            <ul class="nav navbar-top-links navbar-right">
                <?php 
                if(isset($_SESSION['cartData']) && count($_SESSION['cartData']['items']) != 0)
                {
                    ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-shopping-cart fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <?php 
                        foreach ($_SESSION['cartData']['items'] as $item_ids_cart) {
                            # code...
                            $itmDetailsCart = ORM::for_table('jst_product_item')
                                                ->table_alias('prod_item')
                                                ->select('prod_item.*')
                                                ->select('prod.name', 'product_name')
                                                ->join('jst_product', array('prod_item.product_id', '=', 'prod.id'), 'prod')
                                                ->order_by_desc('createdon')
                                                ->find_one($item_ids_cart);
                                                ?>

                            <li>
                                <a href="#">
                                    <div>
                                        <i class="fa fa-star fa-fw"></i> <?php echo $itmDetailsCart->item_name; ?> - <?php echo $itmDetailsCart->uniqueid; ?>
                                        <span class="pull-right text-muted small"><a href="addremovecart.php?iid=<?php echo $itmDetailsCart->id; ?>&act=rem" class="btn btn-info btn-xs">Remove</a></span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>

                                                <?php 
                        }
                        ?>
                        <a href="checkout.php" style="padding:1px 5px; min-height:0px; margin-right:4px;" class="btn btn-info pull-right">Checkout</a>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <?php 
                }
                ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i> New Comment
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                    <span class="pull-right text-muted small">12 minutes ago</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>

                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a></li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a></li>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->