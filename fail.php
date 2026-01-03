<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Failed</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<style>
body {
    background: #f8f9fa;
}

.message-box {
    animation: shake 0.6s ease;
}

._failed {
    box-shadow: 0 15px 25px #00000019;
    padding: 45px;
    text-align: center;
    margin: 80px auto;
    border-bottom: solid 4px red;
    background: #fff;
}

._failed i {
    font-size: 60px;
    color: red;
}

._failed h2 {
    margin-top: 15px;
    font-size: 36px;
}

._failed p {
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

@keyframes shake {
    0% { transform: translateX(0); }
    25% { transform: translateX(-8px); }
    50% { transform: translateX(8px); }
    75% { transform: translateX(-5px); }
    100% { transform: translateX(0); }
}
</style>
</head>

<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="message-box _failed">
                <i class="fa fa-times-circle"></i>
                <h2>Your payment failed</h2>
                <p>Please try again later.</p>
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
