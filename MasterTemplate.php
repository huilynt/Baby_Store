<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ECAD Assignment</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- Site specific Cascading Stylesheet -->
    <link rel="stylesheet" href='css/site.css'>
</head>

<body>
    <div>
        <!-- <div class="row">
            <div class="col-sm-12">
                <a href="index.php">
                    <img src="Images/mamayaebooks.jpg" alt="Logo" class="img-fluid" style="width:100%">
                </a>
            </div>
        </div> -->
        <div class="row">
            <div class="col-sm-12">
                <?php include("navbar.php"); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="container pt-3">
                    <?php echo $MainContent; ?>
                </div>
            </div>
        </div>

        <!-- <div class="row">
            <div class="col-sm-12" style="text-align:right;">
                <hr />
                Do you need help? Please email to:
                <a href="mailto:mamaya@mp.edu.sg">mamaya@np.edu.sg</a>
                <p style="font-size:12px;">&copy; Copyright by Mamaya Group</p>
            </div>
        </div> -->
    </div>
</body>

</html>