< !DOCTYPE html >
    < html >
    < head >
    <? include 'head.php'; ?>
< link rel = "stylesheet"
type = "text/css"
href = "styles.css" >
    < meta charset = "UTF-8" >
    < title > About me < /title> < /head> < body >
    <? include './nav_bar.php' ?>
    <?
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

function test_input($key, $regex) {
    if (!isset($_POST[$key]) || empty($_POST[$key])) {
        $GLOBALS[$key] = "";
        return "Non optional field";
    } else {
        $value = clean_input($_POST[$key]);
        $GLOBALS[$key] = $value;
        if (!preg_match($regex, $value)) {
            return "Invalid input";
        }
        return "";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name_error = test_input("name", "/^[\w\s]+$/");
    $description_error = test_input("description", "/^(awesome)|(coolest)|(thebest)$/");
    $email_error = test_input("email", "/^[a-zA-Z0-9_.]+@[a-zA-Z0-9_.]+\.[a-zA-Z0-9_.]+$/");
    $comment = clean_input($_POST["comment"]);
    if (!empty($description_err)) {
        $description = "awesome";
    }
    $success = empty($name_error) && empty($description_error) && empty($email_error);
    if ($success) {
        $success = mail("d1618033@gmail.com", "Greetings from $name", "Name: $name\nEmail: $email\nThey thought I was $description\nComments:\n$comment");
        if ($success) {
            $msg = "Successfully sent your details to me! I'll get back to you ASAP";
        } else {
            $msg = "Could not send information. Try again later.";

        }
    } else {
        $msg = "Oh no! There seems to be some issues. Check below.";
    }
} ?>
< div id = "content" >
    < p > < b > < span class = "<?if (isset($success) && !$success){echo "
error ";}?>" > <?
    if (isset($msg)) {
        echo "$msg";
    } ?> < /span></b > < /p> < p > Hi! < /p> < p > My name is David Sternlicht. < /p> < p > I 'm 23 years old.</p> < p > I studied Statistics and Economics in Hebrew University and graduated top of my class(with a 98.57 average: )) < /p> < p > I 've always enjoyed programming, and ended up finding myself a job as a software developer in a <a href="http://www.azurepcr.com">startup</a> in Tel Aviv.</p> < p > I 've worked mostly with Python, Matlab and Java.</p> < p > I did some Javascript stuff a
while back, and I thought I 'd try to learn a little more about the web.</p> < p > ... < /p> < p > But that 's enough about me, what about you?</p> < div class = "user_form" >
    < form action = ""
method = "post" >
    < p > Hi, my name is < input name = "name"
type = "text"
value = "<?echo "
$name "?>"
placeholder = "your name goes here" > < span class = "error" > * <? echo " $name_error" ?> < /span></p >
    < p > And I think you 're <select name="description"> < option value = "awesome" <?
    if ($description == "awesome") {
        echo "selected";
    } ?> > awesome < /option> < option value = "coolest" <?
    if ($description == "coolest") {
        echo "selected";
    } ?> > the coolest < /option> < option value = "thebest" <?
    if ($description == "thebest") {
        echo "selected";
    } ?> > simply the best < /option> < span class = "error" > <? echo " $description_error" ?> < /span> < /select> < p > Here 's my email: <input name="email" type="text" value="<?echo "$email"?>" placeholder="your email goes here"><span class="error">* <?echo "$email_error"?></span></p> < p > Here 's what I want to say:</p> < textarea name = "comment"
rows = "4"
cols = "50"
placeholder = "your comment goes here" > <? echo $comment; ?> < /textarea><br/ >
< input type = "submit"
value = "send to me" >
    < /form> < /div> < /div> < /body> < /html>