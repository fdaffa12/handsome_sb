<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dept_name = $_POST['dept_name'];
    $dept_long_name = $_POST['dept_long_name'];

    // Insert department data without DEPT_ID
    $sql = "INSERT INTO hs_departement (DEPT_NAME, DEPT_LONG_NAME)
    VALUES ('$dept_name', '$dept_long_name')";

    if ($conn->query($sql) === TRUE) {
        // Get the last inserted ID
        $last_id = $conn->insert_id;
        
        // Create DEPT_ID by combining ID and DEPT_NAME
        $dept_id = $last_id . $dept_name;
        
        // Update the record with DEPT_ID
        $update_sql = "UPDATE hs_departement SET DEPT_ID='$dept_id' WHERE ID=$last_id";
        if ($conn->query($update_sql) === TRUE) {
            $message = "New department created successfully";
            $message_type = "success";
        } else {
            $message = "Error updating DEPT_ID: " . $conn->error;
            $message_type = "danger";
        }
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
        $message_type = "danger";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Department Management</title>
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
        <a class="nav-link active" href="departement.php">Departement</a>
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

<div class="container">
    <h2>Department Management</h2>
    
    <?php if (isset($message)) { ?>
        <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
    <?php } ?>

    <h3>Create Department</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <div class="form-group">
        <label for="dept_name">DEPT_NAME:</label>
        <input type="text" class="form-control" id="dept_name" name="dept_name" required>
      </div>
      <div class="form-group">
        <label for="dept_long_name">DEPT_LONG_NAME:</label>
        <input type="text" class="form-control" id="dept_long_name" name="dept_long_name" required>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <h3 class="mt-5">Departments List</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>DEPT_ID</th>
                <th>DEPT_NAME</th>
                <th>DEPT_LONG_NAME</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT ID, DEPT_ID, DEPT_NAME, DEPT_LONG_NAME FROM hs_departement";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data dari setiap row
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["ID"]. "</td>
                        <td>" . $row["DEPT_ID"]. "</td>
                        <td>" . $row["DEPT_NAME"]. "</td>
                        <td>" . $row["DEPT_LONG_NAME"]. "</td>
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
