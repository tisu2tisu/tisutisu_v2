<?php 
	/* membuat class login system */
	
	class user
	{
		private $db; // Menyimpan Koneksi ke Database
		private $error; // Menyimpan Error Message
		
		// Constructor untuk class user, membutuhkan satu parameter yaitu koneksi ke database
		function __construct($con)
		{
			$this->db = $con;
			
			if(!isset($_SESSION))
			{
			// mulai session
			session_start();
			}
		}
		
		public function encrypt($var)
		{
			$result = password_hash($var, PASSWORD_DEFAULT);
			return $result;
		}
		
		public function email($var)
		{
			try
			{
			$query = $this->db->prepare("SELECT * FROM user WHERE username=:user");
			$query->execute(array(":user" => $var));
			$row = $query->fetch();
			
			if($query->rowCount() > 0)
			{
				echo $row['email'];
			}
			else
			{
				echo "error!";
			}
			}
			catch(PDOException $e)
			{
				echo $e->getErrorMessage();
			}
		}
				
		public function status($var)
		{
			try
			{
			$query = $this->db->prepare("SELECT * FROM user WHERE username=:user");
			// $query->bindParam( array(":user" => $var));
			$query->execute(array(":user" => $var));
			$row = $query->fetch();
			
			if($query->rowCount() > 0)
			{
				if($row['status'] == 1 )
				{
					echo "Member";
				} else if ($row['status'] == 2)
				{
					echo "Supervisor";
				} else if ($row['status'] == 3)
				{
					echo "Administrator";
				}
				else 
				{
					echo "Error!";
				}
			}
			else
			{
				echo "error!";
			}
			}
			catch(PDOException $e)
			{
				echo $e->getErrorMessage();
			}
		}
		
		public function lastlogin($var)
		{
			try
			{
			$query = $this->db->prepare("SELECT * FROM user WHERE username=:user");
			$query->execute(array(":user" => $var));
			$row = $query->fetch();
			
			if($query->rowCount() > 0)
			{
				return $row['login'];
			}
			else
			{
				echo "error!";
			}
			}
			catch(PDOException $e)
			{
				echo $e->getErrorMessage();
			}
		}
	
		
		
		// Registrasi user baru
		public function register($nama, $password, $email, $status, $login)
		{
			try
			{
				// Buat hash password yang dimasukkan
				$hashPasswd = password_hash($password, PASSWORD_DEFAULT);
				
				// Masukkan user baru ke database
				$query = $this->db->prepare("INSERT INTO user(username, password, email, status, login) VALUES (:username, :password, :email, :status, :login)");
				$query->bindParam(":username", $nama);
				$query->bindParam(":password", $hashPasswd);
				$query->bindParam(":email", $email);
				$query->bindParam(":status", $status);
				$query->bindParam(":login", $login);
				$query->execute();
				
				return true;
			}
			catch(PDOException $e)
			{
				if($e->errorInfo[0] == 23000)
				{
					// error[0] berisi informasi error tentang query sql yang baru dijalankan
					// 23000 adalah kode error ketika ada data yang sama pada kolom yang di set unique
				$this->error = "Email sudah di gunakan!";
				return false;
				}
				else
				{
				echo $e->getMessage();
				}
			}
		}
			
			// Login user
			public function login($username, $password)
			{
				try
				{
					// Ambil Data dari database
					$query = $this->db->prepare("SELECT * FROM user WHERE username = :username");
					$query->bindParam(":username", $username);
					$query->execute();
					$data = $query->fetch();
					
					// Jika jumlah baris > 0 
					
					if($query->rowCount() > 0)
					{
						// jika password yang dimasukkan sesuai dengan yang ada di database
						if(password_verify($password, $data['password']))
						{
							$_SESSION['user_session'] = $data['username'];
							return true;
						}
						else
						{
						$this->error = "Username atau Password Salah";
						return false;
						}
					}
					else
					{
						$this->error = "Tidak ada data di database!";
						return false;
					}
				}
				catch(PDOException $e)
				{
					echo $e->getMessage();
					return false;
				}
			}
			
			// Cek apakah user sudah login
			public function isLoggedIn(){
				// Apakah user_session sudah ada di session
				if(isset($_SESSION['user_session']))
				{
					return true;
				}
			}
			
			// ambil data user yang sudah login
			public function getUser()
			{
				// cek apakah sudah login
				if(!$this->isLoggedIn())
				{
					return false;
				}
				
				try
				{
					// Ambil data user dari database
					$query = $this->db->prepare("SELECT * FROM user WHERE id = :id");
					$query->bindParam(":id", $_SESSION['user_session']);
					$query->execute();
					return $query->fetch();
				}
				catch(PDOException $e)
				{
					echo $e->getMessage();
					return false;
				}
			}
			
			// Logout User 
			public function logout()
			{
				// Hapus Session
				session_destroy();
				// Hapus user_session
				unset($_SESSION['user_session']);
				return true;
			}
			
			public function limit_words($string, $limit)
			{
				$string = explode(" ",$string);
				return implode(" ",array_splice($string,0,$limit));
			}
			
			// ambil error terakhir yang disimpan di variable error
			public function getLastError()
			{
				return $this->error;
			}
		
			// --------------------------- CMS NOW --------------------------------------
			public function createpost($username, $judul, $isi, $created, $url)
			{
				try
				{
				$url = strtolower($url);
				$url = str_replace(array('ä','ü','ö','ß'),array('ae','ue','oe','ss'),$url);
				$url = preg_replace('#[^\w\säüöß]#',null,$url);
				$url = preg_replace('#[\s]{2,}#',' ',$url);
				$url = str_replace(array(' '),array('-'),$url);
				
				$stmt = $this->db->prepare("SELECT * FROM content WHERE judul=:judul");
				$stmt->execute(array(":judul" => $judul));
				
				if($stmt->rowCount() > 0)
				{
					echo " Judul Tidak Boleh sama dengan yang sebelumnya ";
					return false;
				}
				
				$query = $this->db->prepare("INSERT INTO content(user, judul, isi, created, url) VALUES (:user, :judul, :isi, :created, :url)");
				$query->bindParam(":user", $username);
				$query->bindParam(":judul", $judul);
				$query->bindParam(":isi", $isi);
				$query->bindParam(":created", $created);
				$query->bindParam(":url", $url);
				$query->execute();
				return true;
				}
				catch(PDOException $e)
				{
					if($e->errorInfo[0] == 23000)
						{
							// error[0] berisi informasi error tentang query sql yang baru dijalankan
							// 23000 adalah kode error ketika ada data yang sama pada kolom yang di set unique
						$this->error = "Email sudah di gunakan!";
						return false;
						}
						else
						{
						echo $e->getMessage();
						}
				}
			}
			// PAGING -         ---------------------------------- ------
			public function lihatdata($query)
			{
				$stmt = $this->db->prepare($query);
				$stmt->execute();
				
				if($stmt->rowCount() > 0 )
				{
					while( $row = $stmt->fetch(PDO::FETCH_ASSOC))
					{
						$string = strip_tags($row['isi']);
						if(strlen($string) > 500)
						{
							// truncate string 
							$string = substr($string, 0, 500);
							
							//make sure it ends in a word so assasinate doesn't become ass ...
							//$string = substr($stringcut, 0, strpos($stringcut, ' ')) . ' ...';
						}
			?>
					<a href="index.php?article=<?php echo $row['url']; ?>" class="judul"><div class="content">
					<p>
					<h1><?php echo $row['judul']; ?></h1>
					<div class="isi-content">
					<?php echo $string . "..."; ?>
					</div>
					</p>
					Di posting oleh: <?php echo $row['user']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Tanggal: <?php echo $row['created']; ?>
					</div>
					</a>
				<?php
					}
				} else {
				?>
					<div class="content">
					<p>
					<img src="img/meepo.png" alt="meepo" /><h1>Tidak ada postingan</h1>
					<div class="isi-content">
					Tidak Ada postingan....
					</div>
					</p>
					</div>
				<?php
				}
			}
			
			public function paging($query, $records_per_page)
			{
				$starting_position = 0;
				if(isset($_GET['page_no']))
				{
					$starting_position = ($_GET['page_no']-1) * $records_per_page;
				}
				$query2 = $query . " order by id desc limit $starting_position, $records_per_page ";
				return $query2;
			}
			
			public function hitung($query)
			{
				$stmt = $this->db->prepare($query);
				$stmt->execute();
				$row = $stmt->rowCount();
				return $row;
			}
			
			public function paginglink($query, $records_per_page)
			{
				$self = $_SERVER['PHP_SELF'];
				$stmt = $this->db->prepare($query);
				$stmt->execute();
				
				$total_no_of_records = $stmt->rowCount();
				
				if($total_no_of_records > 0)
				{
			?>
					<ul class="pagination">
			<?php
					$total_no_of_pages = ceil($total_no_of_records/$records_per_page);
					$current_page = 1;
					if(isset($_GET['page_no']))
					{
						$current_page = $_GET['page_no'];
					}
					
					if($current_page !=1)
					{
						$previous = $current_page-1;
						echo "<li><a href='" . $self . "?page_no=1'>First</a></li>&nbsp;&nbsp;";
						echo "<li><a href='" . $self . "?page_no=" . $previous . "'>Previous</a></li>&nbsp;&nbsp;";
					}
					
					for($i = 1; $i<$total_no_of_pages; $i++)
					{
						if($i == $current_page)
						{
							echo "<li><a href='" . $self . "?page_no=" . $i . "' style='color:red;'>" . $i . "</a></li>&nbsp;&nbsp;";
						}
						else 
						{
							echo "<li><a href='" . $self . "?page_no=" . $i . "'>" . $i . "</a></li>&nbsp;&nbsp;";
						}
					}
					if($current_page != $total_no_of_pages)
					{
						$next = $current_page+1;
						echo "<li><a href='" . $self . "?page_no=" . $next . "'>Next</a></li>&nbsp;&nbsp;";
						echo "<li><a href='" . $self . "?page_no=" . $total_no_of_pages. "'>Last</a></li>&nbsp;&nbsp;";
					}
				?>
				</ul>
				<?php
				}
			}
		
			public function convert_to_slug($str)
			{
				$str = strtolower(trim($str));
				$str = preg_replace('/[^a-z0-9-]/', '-', $str);
				$str = preg_replace('/-+/', "-", $str);
				rtrim($str, "-");
				return $str;
			}
			
			function convert_to_string($string){
				$string = str_replace('-', ' ',$string);
				return $string;
			}
			
			function show_all_post($username)
			{
				$stmt = $this->db->prepare("SELECT * FROM content WHERE user=:username");
				$stmt->execute(array(":username" => $username));
				
				if($stmt->rowCount() > 0)
				{
			?>
				<table border="1" width="100%">
					<th>ID</th>
					<th>Judul</th>
					<th>Created</th>
					<th>Last Modified</th>
					<th>Option</td>
			<?php
					while($row = $stmt->fetch(PDO::FETCH_ASSOC))
					{
			?>
					<tr colspan="5">
						<td><?php echo $row['id']; ?> </td>
						<td><a href="index.php?article=<?php echo $row['url']; ?>"><?php 
						$string = explode(" ",$row['judul']);
						$string = implode(" ",array_splice($string,0,7));
						echo $string . " ..."; ?></a></td>
						<td><?php echo $row['created']; ?></td>
						<td><?php echo $row['modified']; ?></td>
						<td><a href="cms.php?edit=<?php echo $row['url']; ?>">Edit</a> | <a href="cms.php?hapus=<?php echo $row['id']; ?>"> Hapus </a>
					</tr>
				
			<?php
					}
			?>
				</table>
			<?php
				}
			}
			
			public function show_post($url)
			{
				try
				{
				
				$stmt = $this->db->prepare("SELECT * FROM content WHERE url=:url");
				$stmt->execute(array(":url" => $url));
				
				if($row = $stmt->rowCount() > 0)
				{
					while($row = $stmt->fetch(PDO::FETCH_ASSOC))
					{
			?>
				<div class="content-showpost">
					<p>
					<h1><?php echo $row['judul']; ?></h1>
					<div class="isi-content">
						<?php echo $row['isi']; ?>
						<br />
						<br />
						<div class="left">Di posting oleh : <?php echo $row['user']; ?></div> 
						<div class="right">Tanggal : <?php echo $row['created']; ?> </div>
					</div>
					</p>
				</div>
				<div class="clear-float"></div>
				<div class="comments">
					<p><h3>Comments : </h3>
					<?php $this->check_comments($row['url']); 
							if(isset($_POST['kirim-comments']))
							{
								if(!isset($_POST['comments']))
								{
									echo "Kolom Komentar Tidak Boleh Kosong!";
									return false;
								}
								$nama = $_POST['nama'];
								$comments = $_POST['comments'];
								$ipaddress = $_POST['ipaddress'];
								$article = $_POST['article'];
								$tanggal = $_POST['tanggal'];
								$this->create_comments($nama, $comments, $tanggal, $article, $ipaddress);
							}
							else
							{
					?>
								<form method="post">
									<table>
										<tr>
											<td>Nama :</td>
										</tr>
										<tr>
											<td><input type="text" name="nama" required /></td>
										</tr>
										<tr>
											<td><input type="hidden" name="ipaddress" value="<?php echo $this->get_ip_user(); ?>" /></td>
										</tr>
										<tr>
											<td><input type="hidden" name="tanggal" value="<?php echo date("Y-m-d"); ?>" /></td>
										</tr>
										<tr>
											<td><input type="hidden" name="article" value="<?php echo $row['url']; ?>" /></td>
										</tr>
										
										<tr>
											<td>Komentar : </td>
										</tr>
										<tr>
										<td><textarea name="comments"></textarea></td>
										</tr>
										<tr>
										<td><button type="submit" name="kirim-comments">Submit</button></td>
										</tr>
								</table>
								</form>
								</p>
							</div>
			<?php
							}
					}
				}
				}
				catch(PDOException $e)
				{
					echo $e->getMessage();
				}
			}
			
			public function show_edit_post($url)
			{
					
				$stmt = $this->db->prepare("SELECT * FROM content WHERE url=:url");
				$stmt->execute(array(":url" => $url));
				
				if($row = $stmt->rowCount() > 0)
				{
					while( $row = $stmt->fetch(PDO::FETCH_ASSOC))
					{
			?>
					<h2><center><b>Edit Content</b></center></h2>
					<form method="post">
						<table>
							
							<tr>
								<td> ID   : <input type="number" name="id" value="<?php echo $row['id']; ?>" readonly /></td>
							</tr>
							<tr>
								<td> User : <input type="text" name="username" value="<?php echo $row['user']; ?>" readonly /> </td> 
							</tr>
							
							<tr>
								<td> Judul : <input type="text" name="judul" value="<?php echo $row['judul']; ?>"/> </td>
							</tr>
							<tr>
								<td> <textarea name="isi" class="tinymce"><?php echo $row['isi']; ?></textarea></td>
							</tr>
							<tr>
								<td> Created  :<input type="text" name="created" value="<?php echo $row['created']; ?>" readonly />  </td>
							</tr>
							<tr>
								<td><input type="hidden" name="modified" value="<?php echo date('Y-m-d'); ?>"/> </td>
							</tr>
							<tr>
								<td><button type="submit" name="edit">Change Data </button>
							</tr>
						</table>
					</form>
				<?php
					}
				}
			}
			
			public function edit_post($id, $username, $judul, $isi, $created, $modified)
			{
				try
				{
								
					
				$stmt = $this->db->prepare("UPDATE content SET user=:user, judul=:judul, isi=:isi, created=:created, modified=:modified WHERE id=:id");
				$stmt->bindParam(":id", $id);
				$stmt->bindParam(":user", $username);
				$stmt->bindParam(":judul", $judul);
				$stmt->bindParam(":isi", $isi);
				$stmt->bindParam(":created", $created);
				$stmt->bindParam(":modified", $modified);
				$stmt->execute();
				echo "<a href='cms.php?showpost=true'>Berhasil mengedit data!, silahkan klik disini untuk kembali :)</a>";
				return true;
				}
				catch(PDOException $e)
				{
					echo $e->getMessage();
				}
			}
			
			
			public function delete_post($id)
			{
				try
				{
					$stmt = $this->db->prepare("DELETE FROM content WHERE id=:id");
					$stmt->execute(array(":id" => $id));
					return true;
				}
				catch(PDOException $e)
				{
					return false;
					echo $e->getMessage();
				}
			}
			
			public function show_edit_profile($username)
			{
					
				$stmt = $this->db->prepare("SELECT * FROM user WHERE username=:username");
				$stmt->execute(array(":username" => $username));
				
				if($row = $stmt->rowCount() > 0)
				{
					while( $row = $stmt->fetch(PDO::FETCH_ASSOC))
					{
			?>
					<h2><center><b>Edit Profile</b></center></h2>
					<form method="post">
						<table>
							
							<tr>
								<td> ID   : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="number" name="id" value="<?php echo $row['id']; ?>" <?php echo $row['status'] == '1' ? 'readonly' : ''; ?>/></td>
							</tr>
							<tr>
								<td> Username : &nbsp;&nbsp;<input type="text" name="username" value="<?php echo $row['username']; ?>"  <?php echo $row['status'] == '1' ? 'readonly' : ''; ?> /> </td> 
							</tr>
							
							<tr>
								<td> Password : &nbsp;&nbsp;&nbsp;<input type="password" name="password" required/> </td>
							</tr>
							<tr>
								<td> Email : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="email" name="email" value="<?php echo $row['email']; ?>" /> </td>
							</tr>
							<?php
							if($row['status'] !== '1')
							{
							?>
							<tr>
								<td> status : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="status">
																							  <option value="1" <?php $row['status'] == '1' ? 'selected' : ''; ?>>Member</option>
																							  <option value="2" <?php $row['status'] == '2' ? 'selected' : ''; ?>>Supervisor</option>
																							  <option value="3" <?php $row['status'] == '3' ? 'selected' : ''; ?>>Administrator</option>
																							  </select></td>
							</tr>
							<?php
							}
							?>
							<tr>
								<td><br /><br /><button type="submit" name="editprofile">Change Data </button>
							</tr>
						</table>
					</form>
				<?php
					}
				}
			}
				
			public function edit_profile($id, $username, $password, $email, $status)
				{
					try
					{
					$hashpasswd = password_hash($password, PASSWORD_DEFAULT);
					$stmt = $this->db->prepare("UPDATE user SET id=:id, username=:username, password=:password, email=:email, status=:status WHERE id=:id");
					$stmt->bindParam(":id", $id);
					$stmt->bindParam(":username", $username);
					$stmt->bindParam(":password", $hashpasswd);
					$stmt->bindParam(":email", $email);
					$stmt->bindParam(":status", $status);
					$stmt->execute();
					return true;
					}
					catch(PDOException $e)
					{
						echo $e->getMessage();
						return false;
					}
				}
			public function get_ip_user()
			{
				if(!empty($_SERVER['HTTP_CLIENT_IP']))
				{
					echo $_SERVER['HTTP_CLIENT_IP'];
				} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
				{
					echo $_SERVER['HTTP_X_FORWARDED_FOR'];
				} else
				{
					echo $_SERVER['REMOTE_ADDR'];
				}
			}

			public function check_comments($url)
			{
				$stmt = $this->db->prepare("SELECT * FROM comments WHERE article=:url");
				$stmt->execute(array(":url" => $url));
				if($stmt->rowCount() > 0)
				{
					while($row = $stmt->fetch(PDO::FETCH_ASSOC))
					{
						echo "<div class='comments-isi'>" . htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8') . "&nbsp;&nbsp;&nbsp;" . $row['tanggal'] . "<br /><br />";
						echo htmlspecialchars($row['comments'], ENT_QUOTES, 'UTF-8') . "</div>";
					}
				}
				else
				{
					echo " <div class='comments-isi'><p>Belum Ada Komentar: Poskan Komentar </div></p>";
				}
			}
			
			function xss_clean($data)
				{
				// Fix &entity\n;
				$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
				$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
				$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
				$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

				// Remove any attribute starting with "on" or xmlns
				$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

				// Remove javascript: and vbscript: protocols
				$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
				$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
				$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

				// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
				$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
				$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
				$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

				// Remove namespaced elements (we do not need them)
				$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

				do
				{
					// Remove really unwanted tags
					$old_data = $data;
					$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
				}
				while ($old_data !== $data);

				// we are done...
				return $data;
				}
			
			public function prevent_spam_comments($ip, $article)
			{
			
				$stmt = $this->db->prepare("SELECT article,ipaddress FROM comments WHERE ipaddress=:ip AND article=:article");
				$stmt->bindParam(":ip", $ip);
				$stmt->bindParam(":article", $article);
				$stmt->execute();
				if($stmt->rowCount() > 0)
				{
					echo "Anda Tidak Boleh Memberi Komentar Sebanyak Lebih dari satu";
					return false;
				}
				else
				{
					echo "Berhasil Pos komentar! silahkan refresh :)";
					return true;
				}
			}
			

			public function create_comments($nama, $comments, $tanggal, $article, $ipaddress)
			{
				try
				{	
					if($this->prevent_spam_comments($ipaddress,$article))
					{
						$nama = $this->xss_clean($nama);
						$comments = $this->xss_clean($comments);
						$stmt = $this->db->prepare("INSERT INTO comments(nama,comments,tanggal,article,ipaddress) VALUES (:nama, :comments, :tanggal, :article, :ipaddress)");	
						$stmt->bindParam(":nama", $nama);
						$stmt->bindParam(":comments", $comments);
						$stmt->bindParam(":tanggal", $tanggal);
						$stmt->bindParam(":article", $article);
						$stmt->bindParam(":ipaddress", $ipaddress);
						$stmt->execute();
						return true;
					}
				}
				catch(PDOException $e)
				{
					echo $e->getMessage();
					return false;
				}
			}
	}
		
	
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
		
				
					
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				