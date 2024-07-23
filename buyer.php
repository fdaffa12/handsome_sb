<?php
include 'config.php';

// Untuk menyimpan pesan setelah proses POST
$message = "";
$message_type = "";

// Menyimpan data baru ke tabel hs_buyer
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buyer_name = $_POST['buyer_name'];
    $buyer_long_name = $_POST['buyer_long_name'];
    $country = $_POST['country'];
    $address = $_POST['address'];
    $pic_name = $_POST['pic_name'];
    $pic_phone_number = $_POST['pic_phone_number'];

    // Mendapatkan ID terakhir yang ada di tabel
    $sql_id = "SELECT MAX(ID) as max_id FROM hs_buyer";
    $result_id = $conn->query($sql_id);
    $row_id = $result_id->fetch_assoc();
    $last_id = $row_id['max_id'] ? $row_id['max_id'] + 1 : 1;

    // Menghasilkan BUYER_ID sebagai kombinasi dari ID dan BUYER_NAME
    $buyer_id = $last_id . $buyer_name;

    $sql = "INSERT INTO hs_buyer (ID, BUYER_ID, BUYER_NAME, BUYER_LONG_NAME, COUNTRY, ADDRESS, PIC_NAME, PIC_PHONE_NUMBER)
            VALUES ('$last_id', '$buyer_id', '$buyer_name', '$buyer_long_name', '$country', '$address', '$pic_name', '$pic_phone_number')";

    if ($conn->query($sql) === TRUE) {
        $message = "New record created successfully";
        $message_type = "success";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
        $message_type = "danger";
    }
}

// Mengambil data pembeli untuk ditampilkan
$sql = "SELECT ID, BUYER_ID, BUYER_NAME, BUYER_LONG_NAME, COUNTRY, ADDRESS, PIC_NAME, PIC_PHONE_NUMBER FROM hs_buyer";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Buyer Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">SB MANAGEMENT SYSTEM</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link active" href="buyer.php">Buyer</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="departement.php">Departement</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="employee.php">Employee</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="mes.php">Mes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="no_process.php">No Process</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="style.php">Style</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container mt-4">
    <h2>Buyer Management</h2>
    
    <?php if ($message) { ?>
        <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
    <?php } ?>

    <h3>Create Buyer</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <div class="form-group">
        <label for="buyer_name">BUYER_NAME:</label>
        <input type="text" class="form-control" id="buyer_name" name="buyer_name" required>
      </div>
      <div class="form-group">
        <label for="buyer_long_name">BUYER_LONG_NAME:</label>
        <input type="text" class="form-control" id="buyer_long_name" name="buyer_long_name">
      </div>
      <div class="form-group">
        <label for="country">COUNTRY:</label>
        <input type="text" class="form-control" id="country" name="country" required>
      </div>
      <div class="form-group">
        <label for="address">ADDRESS:</label>
        <input type="text" class="form-control" id="address" name="address" required>
      </div>
      <div class="form-group">
        <label for="pic_name">PIC_NAME:</label>
        <input type="text" class="form-control" id="pic_name" name="pic_name" required>
      </div>
      <div class="form-group">
        <label for="pic_phone_number">PIC_PHONE_NUMBER:</label>
        <input type="text" class="form-control" id="pic_phone_number" name="pic_phone_number" required>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <h3 class="mt-5">Buyers List</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>BUYER_ID</th>
                <th>BUYER_NAME</th>
                <th>BUYER_LONG_NAME</th>
                <th>COUNTRY</th>
                <th>ADDRESS</th>
                <th>PIC_NAME</th>
                <th>PIC_PHONE_NUMBER</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1; // Inisialisasi penghitung untuk kolom No
        if ($result->num_rows > 0) {
            // Output data dari setiap row
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $no++ . "</td> <!-- Menampilkan nomor urut -->
                        <td>" . htmlspecialchars($row["BUYER_ID"]) . "</td>
                        <td>" . htmlspecialchars($row["BUYER_NAME"]) . "</td>
                        <td>" . htmlspecialchars($row["BUYER_LONG_NAME"]) . "</td>
                        <td>" . htmlspecialchars($row["COUNTRY"]) . "</td>
                        <td>" . htmlspecialchars($row["ADDRESS"]) . "</td>
                        <td>" . htmlspecialchars($row["PIC_NAME"]) . "</td>
                        <td>" . htmlspecialchars($row["PIC_PHONE_NUMBER"]) . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>0 results</td></tr>";
        }
        $conn->close();
        ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
