	</div>
	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>	
	<script type="text/javascript" src="script/script.js"></script>
</body>
<br>
    <footer>
        <?php if(Session::is_connected())echo '<p>Bonjour '. ModelUtilisateur::select($_SESSION['mail'])->afficher() . ' ! </p>'; ?>
    	<p>Workflow de The Square Corp.</p>
    </footer>
</html>