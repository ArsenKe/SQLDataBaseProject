<?php
session_start();
if(!isset($_SESSION['user_name'])) {
    header("Location:index.php");
} else {
    $user_name = $_SESSION['user_name'];
    $full_name = $_SESSION['full_name'];
}
?>

<?php 
    $strSearch = "";
    if(isset($_GET['strSearch']))
        $strSearch= $_GET['strSearch'];
?>

<?php require_once('header.php') ?>

<div class="container">

    <div class="row justify-content-md-center">

        <div class="col-md-4 p-4">
            <div class="card pointer text-center " onclick="window.location = 'list.php?table=customer';" >
                <div class="card-header">Customer</div>
                <div class="card-body">
                    <?php 
                        $result = $conn->query("select count(*) as count from customer");
                        echo "<h2>" . $result[0]['COUNT'] . "</h2>";
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 p-4">
            <div class="card pointer text-center " onclick="window.location = 'list.php?table=category';" >
                <div class="card-header">Category</div>
                <div class="card-body">
                    <?php 
                        $result = $conn->query("select count(*) as count from category");
                        echo "<h2>" . $result[0]['COUNT'] . "</h2>";
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 p-4">
            <div class="card pointer text-center " onclick="window.location = 'list.php?table=product';" >
                <div class="card-header">Product</div>
                <div class="card-body">
                    <?php 
                        $result = $conn->query("select count(*) as count from product");
                        echo "<h2>" . $result[0]['COUNT'] . "</h2>";
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 p-4">
            <div class="card pointer text-center " onclick="window.location = 'list.php?table=orders';" >
                <div class="card-header">Order</div>
                <div class="card-body">
                    <?php 
                        $result = $conn->query("select count(*) as count from orders");
                        echo "<h2>" . $result[0]['COUNT'] . "</h2>";
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 p-4">
            <div class="card pointer text-center " onclick="window.location = 'list.php?table=cart';" >
                <div class="card-header">cart</div>
                <div class="card-body">
                    <?php 
                        $result = $conn->query("select count(*) as count from cart");
                        echo "<h2>" . $result[0]['COUNT'] . "</h2>";
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 p-4">
            <div class="card pointer text-center " onclick="window.location = 'list.php?table=shipment';" >
                <div class="card-header">Shipment</div>
                <div class="card-body">
                    <?php 
                        $result = $conn->query("select count(*) as count from shipment");
                        echo "<h2>" . $result[0]['COUNT'] . "</h2>";
                    ?>
                </div>
            </div>
        </div>


    </div>
</div>

<!-- Del Modal -->
<div class="modal fade" id="delModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Delete Record</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                Are you sure you want to delete this record?
                <p></p>
            </div>

            <div class="modal-footer">
                <a href="#" class=".btn-action btn btn-warning">Yes</a>
                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>

        </div>
    </div>
</div>

<?php require_once('footer.php') ?>

<script>
$('#delModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var id = button.data('id')
    var title = button.data('title')
    var action = button.data('action')

    var modal = $(this)
    modal.find('.modal-body p').text(id + ' - ' + title)
    modal.find('.modal-footer a').attr('href', action)
});
</script>