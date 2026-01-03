<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Cancelled</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<style>
body {
    background: #f8f9fa;
}

._cancel {
    box-shadow: 0 15px 25px #00000019;
    padding: 45px;
    text-align: center;
    margin: 80px auto;
    border-bottom: solid 4px #ffc107;
    background: #fff;
    animation: fadeIn 1s ease;
}

._cancel i {
    font-size: 60px;
    color: #ffc107;
}

._cancel h2 {
    margin-top: 15px;
    font-size: 36px;
}

._cancel p {
    font-size: 18px;
    color: #495057;
}

.action-btns {
    margin-top: 25px;
}

.action-btns a {
    margin: 6px;
    min-width: 160px;
    transition: all 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>
</head>

<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="_cancel">
                <i class="fa fa-exclamation-circle"></i>
                <h2>Payment Cancelled</h2>
                <p>You cancelled the payment process.</p>
            </div>
        </div>
    </div>
    <div class="text-center">
        <div class="action-btns">
            <a href="index.php" class="btn btn-success">
                <i class="fa fa-home"></i> Back to Home
            </a>
        </div>
    </div>
</div>
</body>
</html>
