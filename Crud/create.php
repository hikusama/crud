<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$Fname = $Lname = $Age = "";
$Fname_err = $Lname_err = $Age_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = $_POST["f_name"];
    if(empty($input_name)){
        $Fname_err = "Please enter your First name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $Fname_err = "Please enter a valid name.";
    } else{
        $Fname = $input_name;
    }
    
    // Validate address
    $input_Lname = trim($_POST["l_name"]);
    if(empty($input_Lname)){
        $Lname_err = "Please enter your Last name.";     
    } else{
        $Lname = $input_Lname;
    }
    
    // Validate salary
    $input_age = trim($_POST["age"]);
    if(empty($input_age)){
        $Age_err = "Please enter your Age.";     
    } elseif(!ctype_digit($input_age)){
        $Age_err = "Please enter a positive integer value.";
    } else{
        $Age = $input_age;
    }
    
    // Check input errors before inserting in database
    if(empty($Fname_err) && empty($Lname_err) && empty($Age_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO students (first_name, last_name, age) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_fname, $param_lname, $param_age);
            
            $param_fname = $Fname;
            $param_lname = $Lname;
            $param_age = $Age;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add <b>student record </b>to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>First name</label>
                            <input type="text" name="f_name" class="form-control <?php echo (!empty($Fname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Fname; ?>">
                            <span class="invalid-feedback"><?php echo $Fname_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Last name</label>
                            <textarea name="l_name" class="form-control <?php echo (!empty($Lname_err)) ? 'is-invalid' : ''; ?>"><?php echo $Lname; ?></textarea>
                            <span class="invalid-feedback"><?php echo $Lname_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Age</label>
                            <input type="text" name="age" class="form-control <?php echo (!empty($Age_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Age; ?>">
                            <span class="invalid-feedback"><?php echo $Age_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>