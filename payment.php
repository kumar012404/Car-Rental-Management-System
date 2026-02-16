<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Secured Payment | Luxury Rental</title>
    <link rel="stylesheet" href="css/pay.css" />
    <script type="text/javascript">
        function preventBack() { window.history.forward(); }
        setTimeout("preventBack()", 0);
        window.onunload = function () { null };
    </script>
</head>

<body>

<?php
    require_once('connection.php');
    session_start();
    
    if(!isset($_SESSION['email'])) {
        header("Location: index.php");
        exit();
    }

    $u_email = $_SESSION['email'];
    $sql = "SELECT * FROM booking WHERE EMAIL='$u_email' ORDER BY BOOK_ID DESC LIMIT 1";
    $res = mysqli_query($con, $sql);
    $booking = mysqli_fetch_assoc($res);
    
    if(!$booking) {
        header("Location: cardetails.php");
        exit();
    }

    $bid = $booking['BOOK_ID'];
    $_SESSION['bid'] = $bid;
    $price = $booking['PRICE'];

    if(isset($_POST['pay'])){
        $cardno = mysqli_real_escape_string($con, $_POST['cardno']);
        $exp = mysqli_real_escape_string($con, $_POST['exp']);
        $cvv = mysqli_real_escape_string($con, $_POST['cvv']);

        if(empty($cardno) || empty($exp) || empty($cvv)){
            echo '<script>alert("Please fill all payment details")</script>';
        } else {
            // Fix: Check for existing payment to avoid duplicate entry error
            $check_pay = mysqli_query($con, "SELECT * FROM payment WHERE BOOK_ID=$bid");
            if(mysqli_num_rows($check_pay) > 0) {
                header("Location: psucess.php");
                exit();
            } else {
                $sql2 = "INSERT INTO payment (BOOK_ID, CARD_NO, EXP_DATE, CVV, PRICE) VALUES ($bid, '$cardno', '$exp', $cvv, $price)";
                $result = mysqli_query($con, $sql2);
                if($result){
                    header("Location: psucess.php");
                    exit();
                } else {
                    echo '<script>alert("Payment failed, please try again")</script>';
                }
            }
        }
    }
?>

    <div class="payment-container">
        <!-- Main Payment Card -->
        <div class="card">
            <h1 class="card__title">Payment Details</h1>
            
            <!-- Credit Card Visual Decor -->
            <div class="card-visual">
                <div class="chip"></div>
                <div class="card-type">VISA</div>
            </div>

            <form method="POST">
                <div class="input-box">
                    <label>Card Number</label>
                    <input type="text" name="cardno" placeholder="xxxx xxxx xxxx xxxx" maxlength="16" required>
                </div>

                <div class="row">
                    <div class="input-box">
                        <label>Expiry Date</label>
                        <input type="text" name="exp" placeholder="MM/YY" maxlength="5" required>
                    </div>
                    <div class="input-box">
                        <label>CVV Code</label>
                        <input type="password" name="cvv" placeholder="xxx" maxlength="3" required>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-pay" name="pay">PAY ₹<?php echo $price; ?> NOW</button>
                    <a href="cancelbooking.php" class="btn btn-cancel">CANCEL BOOKING</a>
                </div>
            </form>
        </div>

        <!-- Checkout Summary -->
        <div class="summary">
            <div>
                <h2 style="font-size: 1.5rem; margin-bottom: 20px;">Checkout Summary</h2>
                <div style="padding: 15px 0; border-bottom: 1px solid var(--glass-border);">
                    <p style="opacity: 0.6; font-size: 0.8rem;">BOOKING ID</p>
                    <p style="font-weight: 600;">#<?php echo $bid; ?></p>
                </div>
                <div style="padding: 15px 0; border-bottom: 1px solid var(--glass-border);">
                    <p style="opacity: 0.6; font-size: 0.8rem;">EMAIL</p>
                    <p style="font-weight: 600;"><?php echo $u_email; ?></p>
                </div>
            </div>

            <div class="price-summary">
                <h3>TOTAL PAYABLE</h3>
                <div class="amount">₹<?php echo $price; ?>/-</div>
                <p style="font-size: 0.75rem; opacity: 0.5; margin-top: 10px;">Includes all taxes and rental fees.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
    <script src="main.js"></script>
</body>
</html>