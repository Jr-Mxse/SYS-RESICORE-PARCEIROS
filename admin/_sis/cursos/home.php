   <header class="dashboard_header">
   	<div class="dashboard_header_title">
   		<h1 class="icon-books">Meus Treinamentos</h1>
   		<p class="dashboard_header_breadcrumbs">
   			<a title="<?= SITE_NAME2; ?>" href="dashboard.php?wc=home">Dashboard</a>
   			<span class="crumb">/</span>
   			Aprender e Crescer
   		</p>
   	</div>
   </header>

   <div class="dashboard_content">
   	<section class="wc_tab_target wc_active" id="courses">
   		<?php
			$Read->ExeRead(DB_EAD_COURSES, "WHERE course_parceiros=1 AND course_status=1");
			if (!$Read->getResult()):
				echo "<div class='trigger trigger_info trigger-none icon-info al_center'>Você ainda não tem cursos {$Admin['user_name']}!</div>";
			else:
				foreach ($Read->getResult() as $CS):
					extract($CS);

					$Read->FullRead("SELECT COUNT(class_id) AS ClassCount, SUM(class_time) AS ClassTime FROM " . DB_EAD_CLASSES . " WHERE course_id = :cs", "cs={$course_id}");
					$ClassCount = $Read->getResult()[0]['ClassCount'];

					$Read->FullRead("SELECT COUNT(student_class_id) as ClassStudentCount FROM " . DB_EAD_STUDENT_CLASSES . " WHERE user_id = :user AND course_id = :course AND student_class_check IS NOT NULL", "user={$Admin['user_id']}&course={$course_id}");
					$ClassStudenCount = $Read->getResult()[0]['ClassStudentCount'];
					$CourseCompletedPercent = ($ClassStudenCount && $ClassCount ? round(($ClassStudenCount * 100) / $ClassCount) : 0);
					$Cover = ajusteFotoCurso($course_cover);

					$CourseBonusOpen = null;
					if (isset($enrollment_bonus)):
						$Read->FullRead("SELECT course_title FROM " . DB_EAD_COURSES . " WHERE course_id = (SELECT course_id FROM " . DB_EAD_ENROLLMENTS . " WHERE enrollment_id = :enrollbonus)", "enrollbonus={$enrollment_bonus}");
						$CourseBonusOpen = "<p class='wc_ead_home_courses_course_bonus icon-heart icon-notext radius wc_tooltip'><span class='wc_tooltip_balloon'>Bônus do curso {$Read->getResult()[0]['course_title']}</span></p>";
					endif;
			?><article class="box box25 wc_ead_course">
   					<div class="wc_ead_home_courses_course_thumb">
   						<?php
							if (!empty($enrollment_end)):
								$EndDayNow = new DateTime();
								$EndDayRow = new DateTime($enrollment_end);
								$EndDayDif = $EndDayNow->diff($EndDayRow);

								if ($EndDayDif->days < 90 || $EndDayDif->invert):
									if ($course_vendor_renew && ($EndDayDif->days < 90 || $EndDayDif->invert)):
										echo "<a target='_blank' href='{$course_vendor_renew}&sck={$course_name}_re' class='wc_ead_home_courses_course_bonus " . ($CourseBonusOpen ? 'wc_ead_home_courses_course_renew' : '') . " icon-fire icon-notext wc_tooltip'><span class='wc_tooltip_balloon icon-fire'>Renove seu acesso a esse curso por mais {$course_vendor_access} meses com 80% de desconto.</span></a>";
									endif;
								endif;
							endif;

							echo $CourseBonusOpen;
							?>
   						<a href="dashboard.php?wc=cursos/curso&slug=<?= $course_name; ?>" title="Acessar o Curso <?= $course_title; ?>">
   							<img alt="<?= $course_title; ?>" title="<?= $course_title; ?>" src="<?= $Cover; ?>" />
   						</a>
   					</div>
   					<div class="progress"><span class="progress_bar" style="width: <?= $CourseCompletedPercent; ?>%"><?= $CourseCompletedPercent; ?>%</span></div>
   					<div class="wc_ead_home_courses_course_content box_content wc_normalize_height">
   						<h1><a href="dashboard.php?wc=cursos/curso&slug=<?= $course_name; ?>" title="Acessar o Curso <?= $course_title; ?>"><?= $course_title; ?></a></h1>
   						<a href="dashboard.php?wc=cursos/curso&slug=<?= $course_name; ?>" class="btn btn_green btn_xlarge btn_pulse" title="Novo Registro"> Acessar Treinamento</a>
   					</div>
   				</article>
   		<?php
				endforeach;
			endif;
			?>
   	</section>
   </div>