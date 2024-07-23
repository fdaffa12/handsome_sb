<?php
include 'config.php';

// Ambil data gaya untuk dropdown
$styles = [];
$sql_styles = "SELECT STYLE_ID, BUYER_ID FROM hs_style";
$result_styles = $conn->query($sql_styles);
if ($result_styles->num_rows > 0) {
    while ($row = $result_styles->fetch_assoc()) {
        $styles[] = $row;
    }
}

// Menyimpan data baru ke tabel hs_no_process
$message = "";
$message_type = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $style_id = $_POST['style_id'];
    $no_process = $_POST['no_process'];
    $smv_gsd = $_POST['smv_gsd'];
    $smv_est = $_POST['smv_est'];
    $process_name_eng = $_POST['process_name_eng'];
    $process_name_ind = $_POST['process_name_ind'];

    // Ambil BUYER_ID berdasarkan STYLE_ID
    $buyer_id = "";
    foreach ($styles as $style) {
        if ($style['STYLE_ID'] == $style_id) {
            $buyer_id = $style['BUYER_ID'];
            break;
        }
    }

    $sql = "INSERT INTO hs_no_process (BUYER_ID, STYLE_ID, NO_PROCESS, SMV_GSD, SMV_EST, PROCESS_NAME_ENG, PROCESS_NAME_IND)
            VALUES ('$buyer_id', '$style_id', '$no_process', '$smv_gsd', '$smv_est', '$process_name_eng', '$process_name_ind')";

    if ($conn->query($sql) === TRUE) {
        $message = "New record created successfully";
        $message_type = "success";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
        $message_type = "danger";
    }
}

// Ambil data proses untuk ditampilkan
$sql_no_processes = "SELECT np.ID, np.STYLE_ID, s.BUYER_ID, np.NO_PROCESS, np.SMV_GSD, np.SMV_EST, np.PROCESS_NAME_ENG, np.PROCESS_NAME_IND
                     FROM hs_no_process np
                     JOIN hs_style s ON np.STYLE_ID = s.STYLE_ID";
$result_no_processes = $conn->query($sql_no_processes);

?>

<!DOCTYPE html>
<html>
<head>
    <title>No Process Management</title>
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
        <a class="nav-link active" href="no_process.php">No Process</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="style.php">Style</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container mt-4">
    <h2>No Process Management</h2>
    
    <?php if ($message) { ?>
        <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
    <?php } ?>

    <h3>Create No Process</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <div class="form-group">
        <label for="style_id">STYLE_ID:</label>
        <select class="form-control" id="style_id" name="style_id" required>
          <?php foreach ($styles as $style) { ?>
            <option value="<?php echo $style['STYLE_ID']; ?>"><?php echo $style['STYLE_ID']; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label for="buyer_id">BUYER_ID:</label>
        <input type="text" class="form-control" id="buyer_id" name="buyer_id" readonly>
      </div>
      <div class="form-group">
        <label for="no_process">NO_PROCESS:</label>
        <input type="number" class="form-control" id="no_process" name="no_process" required>
      </div>
      <div class="form-group">
        <label for="smv_gsd">SMV_GSD:</label>
        <input type="number" class="form-control" id="smv_gsd" name="smv_gsd">
      </div>
      <div class="form-group">
        <label for="smv_est">SMV_EST:</label>
        <input type="number" class="form-control" id="smv_est" name="smv_est" required>
      </div>
      <div class="form-group">
        <label for="process_name_eng">PROCESS_NAME_ENG:</label>
        <input type="text" class="form-control" id="process_name_eng" name="process_name_eng" required>
      </div>
      <div class="form-group">
        <label for="process_name_ind">PROCESS_NAME_IND:</label>
        <input type="text" class="form-control" id="process_name_ind" name="process_name_ind" required>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <h3 class="mt-5">No Process List</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>STYLE_ID</th>
                <th>BUYER_ID</th>
                <th>NO_PROCESS</th>
                <th>SMV_GSD</th>
                <th>SMV_EST</th>
                <th>PROCESS_NAME_ENG</th>
                <th>PROCESS_NAME_IND</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1; // Inisialisasi penghitung untuk kolom No
        if ($result_no_processes->num_rows > 0) {
            // Output data dari setiap row
            while($row = $result_no_processes->fetch_assoc()) {
                echo "<tr>
                        <td>" . $no++ . "</td> <!-- Menampilkan nomor urut -->
                        <td>" . htmlspecialchars($row["STYLE_ID"]) . "</td>
                        <td>" . htmlspecialchars($row["BUYER_ID"]) . "</td>
                        <td>" . htmlspecialchars($row["NO_PROCESS"]) . "</td>
                        <td>" . htmlspecialchars($row["SMV_GSD"]) . "</td>
                        <td>" . htmlspecialchars($row["SMV_EST"]) . "</td>
                        <td>" . htmlspecialchars($row["PROCESS_NAME_ENG"]) . "</td>
                        <td>" . htmlspecialchars($row["PROCESS_NAME_IND"]) . "</td>
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

<script>
    // Script untuk mengupdate BUYER_ID berdasarkan STYLE_ID yang dipilih
    document.getElementById('style_id').addEventListener('change', function() {
        var styleId = this.value;
        var buyerInput = document.getElementById('buyer_id');
        var buyers = <?php echo json_encode($styles); ?>;
        
        for (var i = 0; i < buyers.length; i++) {
            if (buyers[i].STYLE_ID === styleId) {
                buyerInput.value = buyers[i].BUYER_ID;
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
