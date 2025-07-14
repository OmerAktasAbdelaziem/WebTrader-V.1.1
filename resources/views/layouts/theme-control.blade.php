<?php if(isset($_GET['theme'])){ ?>
    <script>
        <?php if($_GET['theme']=='dark-theme'){ ?>

            $("html").attr("class", "dark-theme");
            localStorage.setItem("theme",'dark-theme');

        <?php }else if($_GET['theme']=='semi-dark'){ ?>

            $("html").attr("class", "semi-dark");
            localStorage.setItem("theme",'semi-dark');

        <?php }else{ ?>

            $("html").attr("class", "light-theme");
            localStorage.setItem("theme","light-theme");

        <?php } ?>
    </script>
    <?php } ?>