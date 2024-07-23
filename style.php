<?php
include 'config.php';

// Ambil data pembeli untuk dropdown
$buyers = [];
$sql_buyers = "SELECT BUYER_ID, BUYER_NAME FROM hs_buyer";
$result_buyers = $conn->query($sql_buyers);
if ($result_buyers->num_rows > 0) {
    while ($row = $result_buyers->fetch_assoc()) {
        $buyers[] = $row;
    }
}

// Menyimpan data baru ke tabel hs_style
$message = "";
$message_type = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $style_id = $_POST['style_id'];
    $buyer_id = $_POST['buyer_id'];
    $category = $_POST['category'];

    $sql = "INSERT INTO hs_style (STYLE_ID, BUYER_ID, CATEGORY)
            VALUES ('$style_id', '$buyer_id', '$category')";

    if ($conn->query($sql) === TRUE) {
        $message = "New record created successfully";
        $message_type = "success";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
        $message_type = "danger";
    }
}

// Ambil data gaya untuk ditampilkan
$sql_styles = "SELECT s.ID, s.STYLE_ID, b.BUYER_NAME, s.CATEGORY
               FROM hs_style s
               JOIN hs_buyer b ON s.BUYER_ID = b.BUYER_ID";
$result_styles = $conn->query($sql_styles);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Style Management</title>
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
        <a class="nav-link" href="buyer.php">Buyer</a>
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
        <a class="nav-link active" href="style.php">Style</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container mt-4">
    <h2>Style Management</h2>
    
    <?php if ($message) { ?>
        <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
    <?php } ?>

    <h3>Create Style</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <div class="form-group">
        <label for="style_id">STYLE_ID:</label>
        <input type="text" class="form-control" id="style_id" name="style_id" required>
      </div>
      <div class="form-group">
        <label for="buyer_id">BUYER:</label>
        <select class="form-control" id="buyer_id" name="buyer_id" required>
          <?php foreach ($buyers as $buyer) { ?>
            <option value="<?php echo $buyer['BUYER_ID']; ?>"><?php echo $buyer['BUYER_NAME']; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label for="category">CATEGORY:</label>
        <select class="form-control" id="category" name="category" required>
          <option value="Top">Top</option>
          <option value="Bottom">Bottom</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <h3 class="mt-5">Styles List</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>STYLE_ID</th>
                <th>BUYER_NAME</th>
                <th>CATEGORY</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1; // Inisialisasi penghitung untuk kolom No
        if ($result_styles->num_rows > 0) {
            // Output data dari setiap row
            while($row = $result_styles->fetch_assoc()) {
                echo "<tr>
                        <td>" . $no++ . "</td> <!-- Menampilkan nomor urut -->
                        <td>" . htmlspecialchars($row["STYLE_ID"]) . "</td>
                        <td>" . htmlspecialchars($row["BUYER_NAME"]) . "</td>
                        <td>" . htmlspecialchars($row["CATEGORY"]) . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>0 results</td></tr>";
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
