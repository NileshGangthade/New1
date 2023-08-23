 <?php
    $department = $_SESSION['department'];
    ?>
    <label for="department">Department:</label>
    <select id="department" name="department" disabled>
      <option value="first-year" <?php if ($department == 'first-year') echo 'selected'; ?>>First Year</option>
      <option value="cse" <?php if ($department == 'cse') echo 'selected'; ?>>Computer Science and Engineering</option>
      <option value="electrical" <?php if ($department == 'electrical') echo 'selected'; ?>>electrical Engineering</option>
      <option value="mech" <?php if ($department == 'mech') echo 'selected'; ?>>Mechanical Engineering</option>
      <option value="civil" <?php if ($department == 'civil') echo 'selected'; ?>>Civil Engineering</option>
      <option value="entc" <?php if ($department == 'entc') echo 'selected'; ?>>Electronics and Telecommunication Engineering</option>
    </select><br><br>

    <!-- Add this hidden input field to store the department value -->
    <input type="hidden" name="department_hidden" value="<?php echo $department; ?>">
