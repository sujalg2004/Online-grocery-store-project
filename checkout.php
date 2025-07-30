<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php';
include 'header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('You must log in before checking out.');
            window.location.href = 'login.php';
          </script>";
    exit();
}


if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<div class='container mt-5'><div class='alert alert-warning text-center'>
            <h4>Your Cart is Empty <i class='fas fa-exclamation-circle'></i></h4>
            <a href='index.php' class='btn btn-primary mt-3'>Continue Shopping</a>
          </div></div>";
    include 'footer.php';
    exit();
}

$total_price = 0;
?>

<style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    .container {
        flex: 1;
    }
    footer {
        background-color: #343a40;
        color: white;
        text-align: center;
        padding: 15px 0;
        margin-top: auto;
    }
    .payment-method {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .payment-method:hover {
        border-color: #28a745;
        background-color: #f8f9fa;
    }
    .payment-method.selected {
        border: 2px solid #28a745;
        background-color: #e8f5e9;
    }
    .payment-details {
        display: none;
        margin-top: 15px;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 5px;
    }
    .upi-apps img {
        width: 50px;
        margin: 5px;
        cursor: pointer;
    }
</style>

<div class="container my-5">
    <h2 class="text-center mb-4">Checkout <i class="fas fa-shopping-cart"></i></h2>

    <form method="POST" action="process_order.php" id="checkoutForm">
        <div class="table-responsive">
            <table class="table table-bordered shadow">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php foreach ($_SESSION['cart'] as $id => $item) : 
                        $total = $item['price'] * $item['quantity'];
                        $total_price += $total;
                    ?>
                    <tr>
                        <td><img src="assets/images/<?php echo htmlspecialchars($item['image']); ?>" width="80" height="80"></td>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td>₹<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>₹<?php echo number_format($total, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <h4 class="mb-3">Payment Method</h4>
                
                
                <div class="payment-method" onclick="selectPaymentMethod('cash')">
                    <input type="radio" name="payment_method" id="cash" value="cash" checked>
                    <label for="cash" style="cursor: pointer;">
                        <i class="fas fa-money-bill-wave"></i> Cash on Delivery
                    </label>
                </div>
                
                <div class="payment-method" onclick="selectPaymentMethod('card')">
                    <input type="radio" name="payment_method" id="card" value="card">
                    <label for="card" style="cursor: pointer;">
                        <i class="fas fa-credit-card"></i> Credit/Debit Card
                    </label>
                    <div class="payment-details" id="cardDetails">
                        <div class="form-group">
                            <label for="card_number">Card Number</label>
                            <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456">
                        </div>
                        <div class="form-group">
                            <label for="card_name">Name on Card</label>
                            <input type="text" class="form-control" id="card_name" name="card_name" placeholder="sujal gupta">
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="card_expiry">Expiry Date</label>
                                <input type="text" class="form-control" id="card_expiry" name="card_expiry" placeholder="MM/YY">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="card_cvv">CVV</label>
                                <input type="text" class="form-control" id="card_cvv" name="card_cvv" placeholder="123">
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <div class="payment-method" onclick="selectPaymentMethod('upi')">
                    <input type="radio" name="payment_method" id="upi" value="upi">
                    <label for="upi" style="cursor: pointer;">
                        <i class="fas fa-mobile-alt"></i> UPI Payment
                    </label>
                    <div class="payment-details" id="upiDetails">
                        <div class="form-group">
                            <label for="upi_id">UPI ID</label>
                            <input type="text" class="form-control" id="upi_id" name="upi_id" placeholder="yourname@upi">
                        </div>
                        <p class="text-muted">Or pay with:</p>
                        <div class="upi-apps text-center">
                            <img src="assets/images/g.webp" alt="Google Pay" title="Google Pay" onclick="selectUPIApp('gpay')">
                            <img src="assets/images/p1.jpg" alt="PhonePe" title="PhonePe" onclick="selectUPIApp('phonepe')">
                            <img src="assets/images/p2.png" alt="Paytm" title="Paytm" onclick="selectUPIApp('paytm')">
                            <img src="assets/images/b.png" alt="BHIM" title="BHIM" onclick="selectUPIApp('bhim')">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>₹<?php echo number_format($total_price, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>FREE</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span>₹0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong class="text-success">₹<?php echo number_format($total_price, 2); ?></strong>
                        </div>
                        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
                        
                        <button type="submit" name="place_order" class="btn btn-success btn-lg btn-block mt-4">
                            <i class="fas fa-lock"></i> Complete Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


<script>
    function selectPaymentMethod(method) {
        document.getElementById(method).checked = true;

       
        document.querySelectorAll('.payment-method').forEach(el => {
            el.classList.remove('selected');
        });
        event.currentTarget.classList.add('selected');

      
        document.querySelectorAll('.payment-details').forEach(el => {
            el.style.display = 'none';
        });

        if (method === 'card') {
            document.getElementById('cardDetails').style.display = 'block';
        } else if (method === 'upi') {
            document.getElementById('upiDetails').style.display = 'block';
        }
    }

    function selectUPIApp(app) {
        alert('Redirecting to ' + app + ' payment...');
        document.getElementById('upi_id').value = 'customer@' + app;
    }

    
    document.getElementById('checkoutForm').addEventListener('submit', function(event) {
        let paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        if (paymentMethod === "card") {
            let cardNumber = document.getElementById('card_number').value.trim();
            let cardName = document.getElementById('card_name').value.trim();
            let cardExpiry = document.getElementById('card_expiry').value.trim();
            let cardCvv = document.getElementById('card_cvv').value.trim();

            if (cardNumber === "" || cardName === "" || cardExpiry === "" || cardCvv === "") {
                alert("Please fill in all the card details.");
                event.preventDefault();
                return false;
            }

           
            let cardNumberPattern = /^[0-9]{16}$/;
            if (!cardNumberPattern.test(cardNumber.replace(/\s+/g, ''))) {
                alert("Enter a valid 16-digit card number.");
                event.preventDefault();
                return false;
            }

           
            let expiryPattern = /^(0[1-9]|1[0-2])\/\d{2}$/;
            if (!expiryPattern.test(cardExpiry)) {
                alert("Enter a valid expiry date in MM/YY format.");
                event.preventDefault();
                return false;
            }

           
            let cvvPattern = /^[0-9]{3}$/;
            if (!cvvPattern.test(cardCvv)) {
                alert("Enter a valid 3-digit CVV.");
                event.preventDefault();
                return false;
            }
        }

        if (paymentMethod === "upi") {
            let upiId = document.getElementById('upi_id').value.trim();

            if (upiId === "") {
                alert("Please enter a valid UPI ID.");
                event.preventDefault();
                return false;
            }

        
            let upiPattern = /^[a-zA-Z0-9.\-_]{2,256}@[a-zA-Z]{2,64}$/;
            if (!upiPattern.test(upiId)) {
                alert("Enter a valid UPI ID.");
                event.preventDefault();
                return false;
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.payment-method').classList.add('selected');
    });
</script>
<footer>
    <p>&copy; <?php echo date("Y"); ?> SG MART.com All Rights Reserved.</p>
</footer>

</body>
</html>