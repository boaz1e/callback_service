<!-- views/leads_table.php -->
<h2>Leads</h2>
<a href="backoffice.php?logout=1">Logout</a>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>IP</th>
            <th>Country</th>
            <th>URL</th>
            <th>Note</th>
            <th>Sub_1</th>
            <th>Called</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($leads as $lead): ?>
            <tr>
                <td><?php echo $lead['id']; ?></td>
                <td><?php echo $lead['first_name']; ?></td>
                <td><?php echo $lead['last_name']; ?></td>
                <td><?php echo $lead['email']; ?></td>
                <td><?php echo $lead['phone_number']; ?></td>
                <td><?php echo $lead['ip']; ?></td>
                <td><?php echo $lead['country']; ?></td>
                <td><?php echo $lead['url']; ?></td>
                <td><?php echo $lead['note']; ?></td>
                <td><?php echo $lead['sub_1']; ?></td>
                <td><?php echo $lead['called'] ? 'Yes' : 'No'; ?></td>
                <td><?php echo $lead['created_at']; ?></td>
                <td>
                    <?php if (!$lead['called']): ?>
                        <form action="backoffice.php" method="POST">
                            <input type="hidden" name="lead_id" value="<?php echo $lead['id']; ?>">
                            <button type="submit" name="mark_called">Mark as Called</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
