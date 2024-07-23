<?php
include 'config.php';

// Ambil data gaya untuk dropdown
$styles = [];
$sql_styles = "SELECT STYLE_ID, BUYER_ID FROM hs_style";
$result_styles = $conn->query($sql_styles);

if ($result_styles === FALSE) {
    die("Error: " . $conn->error);
}

if ($result_styles->num_rows > 0) {
    while ($row = $result_styles->fetch_assoc()) {
        $styles[] = $row;
    }
}

// Menyimpan data baru ke tabel sb_mes
$message = "";
$message_type = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $factory = $_POST['factory'];
    $line = $_POST['line'];
    $style_id = $_POST['style_id'];
    $enterance_date = $_POST['enterance_date'];
    $worker = $_POST['worker'];
    $working_h = $_POST['working_h'];

    // Ambil BUYER_ID berdasarkan STYLE_ID
    $buyer_id = "";
    foreach ($styles as $style) {
        if ($style['STYLE_ID'] == $style_id) {
            $buyer_id = $style['BUYER_ID'];
            break;
        }
    }

    // Hitung WORKING_DAYS berdasarkan ENTERANCE_DATE
    $current_date = new DateTime(); // Tanggal hari ini
    $enterance_date_obj = new DateTime($enterance_date);
    $interval = $enterance_date_obj->diff($current_date);
    $working_d = $interval->days;

    $sql_insert = "INSERT INTO sb_mes (DATE, FACTORY, LINE, BUYER_ID, STYLE_ID, ENTERANCE_DATE, WORKER, WORKING_H, WORKING_D)
                   VALUES ('$date', '$factory', '$line', '$buyer_id', '$style_id', '$enterance_date', '$worker', '$working_h', '$working_d')";

    if ($conn->query($sql_insert) === TRUE) {
        $message = "New record created successfully";
        $message_type = "success";
    } else {
        $message = "Error: " . $sql_insert . "<br>" . $conn->error;
        $message_type = "danger";
    }
}

// Ambil data mes untuk ditampilkan
$sql_mes = "SELECT * FROM sb_mes";
$result_mes = $conn->query($sql_mes);

if ($result_mes === FALSE) {
    die("Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Mes Management</title>
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
        <a class="nav-link active" href="mes.php">Mes</a>
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
    <h2>Mes Management</h2>
    
    <?php if ($message) { ?>
        <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
    <?php } ?>

    <h3>Create New Mes Entry</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <div class="form-group">
        <label for="date">Date:</label>
        <input type="date" class="form-control" id="date" name="date" required>
      </div>
      <div class="form-group">
        <label for="factory">Factory:</label>
        <input type="number" class="form-control" id="factory" name="factory" required>
      </div>
      <div class="form-group">
        <label for="line">Line:</label>
        <input type="number" class="form-control" id="line" name="line" required>
      </div>
      <div class="form-group">
        <label for="style_id">STYLE_ID:</label>
        <select class="form-control" id="style_id" name="style_id" required>
          <?php foreach ($styles as $style) { ?>
            <option value="<?php echo htmlspecialchars($style['STYLE_ID']); ?>"><?php echo htmlspecialchars($style['STYLE_ID']); ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label for="buyer_id">BUYER_ID:</label>
        <input type="text" class="form-control" id="buyer_id" name="buyer_id" readonly>
      </div>
      <div class="form-group">
        <label for="enterance_date">Enterance Date:</label>
        <input type="date" class="form-control" id="enterance_date" name="enterance_date" required>
      </div>
      <div class="form-group">
        <label for="worker">Worker:</label>
        <input type="number" class="form-control" id="worker" name="worker" required>
      </div>
      <div class="form-group">
        <label for="working_h">Working Hours:</label>
        <input type="number" class="form-control" id="working_h" name="working_h" required>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <h3 class="mt-5">Mes List</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Factory</th>
                <th>Line</th>
                <th>Buyer ID</th>
                <th>Style ID</th>
                <th>Enterance Date</th>
                <th>Worker</th>
                <th>Working Hours</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result_mes->num_rows > 0) {
            // Output data dari setiap row
            while($row = $result_mes->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["ID"]) . "</td>
                        <td>" . htmlspecialchars($row["DATE"]) . "</td>
                        <td>" . htmlspecialchars($row["FACTORY"]) . "</td>
                        <td>" . htmlspecialchars($row["LINE"]) . "</td>
                        <td>" . htmlspecialchars($row["BUYER_ID"]) . "</td>
                        <td>" . htmlspecialchars($row["STYLE_ID"]) . "</td>
                        <td>" . htmlspecialchars($row["ENTERANCE_DATE"]) . "</td>
                        <td>" . htmlspecialchars($row["WORKER"]) . "</td>
                        <td>" . htmlspecialchars($row["WORKING_H"]) . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='9'>0 results</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<script>
    // Script untuk mengupdate BUYER_ID berdasarkan STYLE_ID yang dipilih
    document.getElementById('style_id').addEventListener('change', function() {
        var styleId = this.value;
        var buyerInput = document.getElementById('buyer_id');
        var styles = <?php echo json_encode($styles); ?>;
        
        for (var i = 0; i < styles.length; i++) {
            if (styles[i].STYLE_ID === styleId) {
                buyerInput.value = styles[i].BUYER_ID;
                return;
            }
        }
        buyerInput.value = '';
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
