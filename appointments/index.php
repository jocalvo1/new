<?php
require('../../includes/staff/appointmentController.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WBDPRMS - Appointments</title>
    <?php include '../../templates/links.php'; ?>
</head>

<body>
    <div class="wrapper">
        <?php include '../templates/sidebar.php'; ?>
        <div class="main-panel">
            <?php include '../templates/topnav.php'; ?>
            <div class="content">
                <div class="row" id="currentAppointment">
                    <div class="card table-with-links">
                        <div class="card-header">
                            <div>
                                <h4 class="card-title float-start">Current Appointment/s</h4>
                                <div class="float-end">
                                    <a href="#upcomingAppointment" class="btn btn-success btn-round">Upcoming Appointments</a>
                                    <a href="#appointmentHistory" class="btn btn-secondary btn-round">Appointment History</a>
                                </div>
                            </div>
                            <input type="text" id="appointmentSearch" class="form-control" placeholder="Search by user name.">
                        </div>
                        <div class="card-body table-full-width">
                            <table class="table" id="appointmentTable">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Full Name</th>
                                        <th>Appointment Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    if ($resultApprovedAppointments->num_rows > 0) {
                                        $hasCurrent = false;
                                        while ($row = $resultApprovedAppointments->fetch_assoc()) {
                                            $appointmentDate = new DateTime($row['appointment_date']);
                                            $formattedDate = $appointmentDate->format('F j, Y');

                                            if ($appointmentDate->format('Y-m-d') === $currentDate->format('Y-m-d')) {
                                                $hasCurrent = true;
                                    ?>
                                            <tr>
                                                <td class="text-center"><?php echo $counter++; ?></td>
                                                <td><?php echo $row['user_firstName'] . ' ' . $row['user_midInitial'] . ' ' . $row['user_lastName']; ?></td>
                                                <td><?php echo $formattedDate; ?></td>
                                                <td class="td-actions">
                                                    <a href="#" data-toggle="modal" data-target="#viewModal<?php echo $row['appointment_id']; ?>" class="btn btn-info btn-link btn-xs" title="View"><i class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                    <?php
                                            }
                                        }
                                        if (!$hasCurrent) {
                                            echo '<tr><td colspan="4" class="text-center">No current appointments!</td></tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="4" class="text-center">No approved appointments found!</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row" id="upcomingAppointment">
                    <div class="card table-with-links">
                        <div class="card-header">
                            <div>
                                <h4 class="card-title float-start">Upcoming Appointment/s</h4>
                                <div class="float-end">
                                    <a href="#currentAppointment" class="btn btn-info btn-round">Current Appointments</a>
                                    <a href="#appointmentHistory" class="btn btn-secondary btn-round">Appointment History</a>
                                </div>
                            </div>
                            <input type="text" id="appointmentSearch" class="form-control" placeholder="Search by user name.">
                        </div>
                        <div class="card-body table-full-width">
                            <table class="table" id="appointmentTable">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Full Name</th>
                                    <th>Appointment Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $counter = 1;
                                $resultApprovedAppointments->data_seek(0); // Reset the pointer to the start of results
                                $hasFuture = false;
                                while ($row = $resultApprovedAppointments->fetch_assoc()) {
                                    $appointmentDate = new DateTime($row['appointment_date']);
                                    if ($appointmentDate > $currentDate) {
                                        $formattedDate = $appointmentDate->format('F j, Y');
                                        $hasFuture = true;
                                ?>
                                        <tr>
                                            <td class="text-center"><?php echo $counter++; ?></td>
                                            <td><?php echo $row['user_firstName'] . ' ' . $row['user_midInitial'] . ' ' . $row['user_lastName']; ?></td>
                                            <td><?php echo $formattedDate; ?></td>
                                            <td class="td-actions">
                                                <a href="#" data-toggle="modal" data-target="#viewModal<?php echo $row['appointment_id']; ?>" class="btn btn-info btn-link btn-xs" title="View"><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                if (!$hasFuture) {
                                    echo '<tr><td colspan="4" class="text-center">No future appointments!</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>

                <div class="row" id="appointmentHistory">
                    <div class="card table-with-links">
                        <div class="card-header">
                            <div>
                                <h4 class="card-title float-start">Appointment History</h4>
                                <div class="float-end">
                                    <a href="#currentAppointment" class="btn btn-info btn-round">Current Appointments</a>
                                    <a href="#upcomingAppointment" class="btn btn-success btn-round">Upcoming Appointment</a>
                                </div>
                            </div>
                            <input type="text" id="appointmentSearch" class="form-control" placeholder="Search by user name.">
                        </div>
                        <div class="card-body table-full-width">
                            <table class="table" id="appointmentTable">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Full Name</th>
                                    <th>Appointment Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $counter = 1;
                                $resultApprovedAppointments->data_seek(0); // Reset the pointer to the start of results
                                $hasPast = false;
                                while ($row = $resultApprovedAppointments->fetch_assoc()) {
                                    $appointmentDate = new DateTime($row['appointment_date']);
                                    if ($appointmentDate < $currentDate) {
                                        $formattedDate = $appointmentDate->format('F j, Y');
                                        $hasPast = true;
                                ?>
                                        <tr>
                                            <td class="text-center"><?php echo $counter++; ?></td>
                                            <td><?php echo $row['user_firstName'] . ' ' . $row['user_midInitial'] . ' ' . $row['user_lastName']; ?></td>
                                            <td><?php echo $formattedDate; ?></td>
                                            <td class="td-actions">
                                                <a href="#" data-toggle="modal" data-target="#viewModal<?php echo $row['appointment_id']; ?>" class="btn btn-info btn-link btn-xs" title="View"><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                if (!$hasPast) {
                                    echo '<tr><td colspan="4" class="text-center">No past appointments!</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php include '../../templates/footer.php'; ?>
        </div>
    </div>
    <script>
        document.getElementById('appointmentSearch').addEventListener('keyup', function() {
            var searchValue = this.value.toLowerCase();
            var tableRows = document.querySelectorAll('#appointmentTable tbody tr');
            tableRows.forEach(function(row) {
                var userName = row.cells[1].textContent.toLowerCase();
                var status = row.cells[3].textContent.toLowerCase();
                if (userName.includes(searchValue) || status.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
