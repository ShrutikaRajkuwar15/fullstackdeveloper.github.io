<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Full Stack Developer Course in Pune</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 2% auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .form-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .form-group label {
            margin-right: 10px;
        }
        .form-group input {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group button {
            padding: 5px 10px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        #btnn {
            display: none;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        #btnn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-group">
            <form action="" method="GET" name="myform">
                <label>From</label>
                <input type="date" name="from_date" value="<?php if (isset($_GET['from_date'])) { echo $_GET['from_date']; } ?>">
                <label>To</label>
                <input type="date" name="to_date" value="<?php if (isset($_GET['to_date'])) { echo $_GET['to_date']; } ?>">
                <button type="submit">Filter</button>
            </form>
        </div>
        <button id="btnn">Download</button>
        <?php
            try {
                $host = 'localhost';
                $port = 3306;
                $dbname = 'maestroi_datacouncilenq';
                $username = 'maestroi_datacouncil_enq';
                $password = 'datacouncil@2024';

                $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
                $con = new PDO($dsn, $username, $password);
                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
                    $from_date = $_GET['from_date'] . " 00:00:00";
                    $to_date = $_GET['to_date'] . " 23:59:59";

                    $query = $con->prepare("SELECT * FROM datacouncil WHERE status=1 AND date BETWEEN :from_date AND :to_date");
                    $query->bindParam(':from_date', $from_date);
                    $query->bindParam(':to_date', $to_date);
                    $query->execute();
                    $query_run = $query->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (count($query_run) > 0) {
                        echo '<table id="tbl">';
                        echo '<tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Date</th>
                              </tr>';
                              $counter = 1;
                        foreach (array_reverse($query_run) as $row) {
                            echo '<tr>
                                    <td>' .$counter .'</td>
                                    <td>' . htmlspecialchars($row['name']) . '</td>
                                    <td>' . htmlspecialchars($row['email']) . '</td>
                                    <td>' . htmlspecialchars($row['phone']) . '</td>
                                    <td>' . htmlspecialchars($row['date']) . '</td>
                                  </tr>'; $counter++;
                        }
                       
                        echo '</table>';
                        echo '<script>document.getElementById("btnn").style.display = "block";</script>';
                    } else {
                        echo "<p>No Record Found</p>";
                    }
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        ?>
    </div>
    <script>
        document.getElementById("btnn").addEventListener("click", function() {
            var table = document.getElementById("tbl");
            var wb = XLSX.utils.table_to_book(table, {sheet: "Sheet JS"});
            var wbout = XLSX.write(wb, {bookType: 'xlsx', type: 'binary'});

            function s2ab(s) {
                var buf = new ArrayBuffer(s.length);
                var view = new Uint8Array(buf);
                for (var i = 0; i != s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
            }

            var blob = new Blob([s2ab(wbout)], {type: "application/octet-stream"});
            var link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "Fullstack.xlsx";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    </script>
</body>
</html>
