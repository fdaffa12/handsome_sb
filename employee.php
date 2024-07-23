<?php
include 'config.php';

// Get department data for dropdown
$departments = [];
$sql = "SELECT ID, DEPT_LONG_NAME FROM hs_departement";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nik = $_POST['nik'];
    $dept_id = $_POST['dept_id'];
    $emp_id = $nik . $dept_id; // Menggabungkan NIK dan DEPT_ID untuk menghasilkan EMP_ID secara otomatis
    $sub_dept = $_POST['sub_dept'];
    $experience = $_POST['experience'];
    $enterance_date = $_POST['enterance_date'];
    $title = $_POST['title'];
    $position = $_POST['position'];

    $sql = "INSERT INTO HS_EMPLOYEE (EMP_ID, NIK, DEPT_ID, SUB_DEPT, EXPERIENCE, ENTERANCE_DATE, TITLE, POSITION)
    VALUES ('$emp_id', '$nik', '$dept_id', '$sub_dept', '$experience', '$enterance_date', '$title', '$position')";

    if ($conn->query($sql) === TRUE) {
        $message = "New record created successfully";
        $message_type = "success";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
        $message_type = "danger";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Management</title>
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
        <a class="nav-link active" href="employee.php">Employee</a>
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

<div class="container">
    <h2>Employee Management</h2>
    
    <?php if (isset($message)) { ?>
        <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
    <?php } ?>

    <h3>Create Employee</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <div class="form-group">
        <label for="nik">NIK:</label>
        <input type="text" class="form-control" id="nik" name="nik" required>
      </div>
      <div class="form-group">
        <label for="dept_id">DEPT:</label>
        <select class="form-control" id="dept_id" name="dept_id" required>
          <?php foreach ($departments as $department) { ?>
            <option value="<?php echo $department['ID']; ?>"><?php echo $department['DEPT_LONG_NAME']; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group">
        <label for="sub_dept">SUB_DEPT:</label>
        <input type="text" class="form-control" id="sub_dept" name="sub_dept">
      </div>
      <div class="form-group">
        <label for="experience">EXPERIENCE:</label>
        <input type="text" class="form-control" id="experience" name="experience">
      </div>
      <div class="form-group">
        <label for="enterance_date">ENTERANCE_DATE:</label>
        <input type="date" class="form-control" id="enterance_date" name="enterance_date">
      </div>
      <div class="form-group">
        <label for="title">TITLE:</label>
        <input type="text" class="form-control" id="title" name="title">
      </div>
      <div class="form-group">
        <label for="position">POSITION:</label>
        <input type="text" class="form-control" id="position" name="position">
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <h3 class="mt-5">Employees List</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>EMP_ID</th>
                <th>NIK</th>
                <th>DEPT</th>
                <th>SUB_DEPT</th>
                <th>EXPERIENCE</th>
                <th>ENTERANCE_DATE</th>
                <th>TITLE</th>
                <th>POSITION</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT e.ID, e.EMP_ID, e.NIK, d.DEPT_LONG_NAME as DEPT_ID, e.SUB_DEPT, e.EXPERIENCE, e.ENTERANCE_DATE, e.TITLE, e.POSITION
                FROM HS_EMPLOYEE e
                JOIN hs_departement d ON e.DEPT_ID = d.ID";
        $result = $conn->query($sql);
        $no = 1;

        if ($result->num_rows > 0) {
            // Output data dari setiap row
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $no++ . "</td> <!-- Menampilkan nomor urut -->
                        <td>" . $row["EMP_ID"]. "</td>
                        <td>" . $row["NIK"]. "</td>
                        <td>" . $row["DEPT_ID"]. "</td>
                        <td>" . $row["SUB_DEPT"]. "</td>
                        <td>" . $row["EXPERIENCE"]. "</td>
                        <td>" . $row["ENTERANCE_DATE"]. "</td>
                        <td>" . $row["TITLE"]. "</td>
                        <td>" . $row["POSITION"]. "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='9'>0 results</td></tr>";
        }
        $conn->close();
        ?>
        </tbody>
    </table>
</div>

</body>
</html>
