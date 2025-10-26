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
   		$Read->FullRead(
   			"SELECT "
   			. "e.*,"
   			. "c.*"
   			. "FROM " . DB_EAD_ENROLLMENTS . " e "
   			. "INNER JOIN " . DB_EAD_COURSES . " c ON c.course_id = e.course_id "
   			. "WHERE e.user_id = :user "
   			. "ORDER BY e.enrollment_access DESC, c.course_order ASC",
   			"user={$Admin['user_id']}");

   		if (!$Read->getResult()):
   			echo "<div class='trigger trigger_info trigger-none icon-info al_center'>Você ainda não tem cursos {$Admin['user_name']}!</div>";
   		else:
   			foreach ($Read->getResult() as $CS):
   				extract($CS);

   				$Read->FullRead("SELECT COUNT(class_id) AS ClassCount, SUM(class_time) AS ClassTime FROM " . DB_EAD_CLASSES . " WHERE course_id = :cs", "cs={$course_id}");
   				$ClassCount = $Read->getResult()[0]['ClassCount'];

   				$Read->FullRead("SELECT COUNT(student_class_id) as ClassStudentCount FROM " . DB_EAD_STUDENT_CLASSES . " WHERE user_id = :user AND course_id = :course AND student_class_check IS NOT NULL", "user={$user_id}&course={$course_id}");
   				$ClassStudenCount = $Read->getResult()[0]['ClassStudentCount'];
   				$CourseCompletedPercent = ($ClassStudenCount && $ClassCount ? round(($ClassStudenCount * 100) / $ClassCount) : 0);
   				$Cover = (file_exists("../uploads/{$course_cover}") && !is_dir("../uploads/{$course_cover}") ? "uploads/{$course_cover}" : 'admin/_img/no_image.jpg');

   				$CourseBonusOpen = null;
   				if ($enrollment_bonus):
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
   						<img alt="<?= $course_title; ?>" title="<?= $course_title; ?>" src=../tim.php?src=<?= $Cover; ?>&w=<?= round(IMAGE_W / 3); ?>&h=<?= round(IMAGE_H / 3); ?>" />
   					</a>
   				</div>
   				<div class="progress"><span class="progress_bar" style="width: <?= $CourseCompletedPercent; ?>%"><?= $CourseCompletedPercent; ?>%</span></div>
   				<div class="wc_ead_home_courses_course_content box_content wc_normalize_height">
   					<h1 class="icon-lab"><a href="dashboard.php?wc=cursos/curso&slug=<?= $course_name; ?>" title="Acessar o Curso <?= $course_title; ?>"><?= $course_title; ?></a></h1>
   					<p class="icon-clock">Minha Matrícula: <?= date("d/m/y", strtotime($enrollment_start)); ?></p>
   					<p class="icon-history">Último acesso: <?= $enrollment_access ? date("d/m H\hi", strtotime($enrollment_access)) : "NUNCA"; ?></p>
   					<?php
   					$Read->LinkResult(DB_EAD_ORDERS, "course_id", $course_id, 'order_signature_plan, order_signature_recurrency, order_signature_period');

   					if (!empty($Read->getResult()[0]['order_signature_plan'])):
   						$order_signature_recurrency = str_pad($Read->getResult()[0]['order_signature_recurrency'], 2, 0, 0);
   						echo "<p class='icon-rss2'>Assinatura {$Read->getResult()[0]['order_signature_plan']}</p>";
   					elseif ($enrollment_end && empty($enrollment_bonus)):
   						if (!$EndDayDif->invert):
   							echo "<p class='icon-heart wc_tooltip'>Mais {$EndDayDif->days} dias para estudar!<span class='wc_tooltip_balloon'>Sua matrícula vence dia " . date("d/m/Y \a\s H\hi", strtotime($enrollment_end)) . "</span></p>";
   						else:
   							echo "<p class='icon-heart-broken'>Acesso expirado a {$EndDayDif->days} dias!</p>";
   						endif;

   					elseif (!empty($enrollment_bonus)):
   						echo "<p class='icon-heart'>Meu bônus de matrícula!</p>";
   					endif;
   					?>
   				</div>
   				<?php
   				if ($course_certification_workload && EAD_STUDENT_CERTIFICATION):
   					if ($ClassCount == 0 || $CourseCompletedPercent < $course_certification_request):
   						echo "<div class='icon-trophy wc_ead_home_courses_certifications wc_tooltip'>CERTIFICADO PENDENTE<span class='wc_tooltip_balloon icon-info'>Complete {$course_certification_request}% do curso para soliticar seu certificado!</span></div>";
   					else:
   						$Read->FullRead("SELECT certificate_key FROM " . DB_EAD_STUDENT_CERTIFICATES . " WHERE enrollment_id = :enrol", "enrol={$enrollment_id}");
   						if (!$Read->getResult()):
   							echo "<div class='icon-trophy wc_ead_home_courses_certifications wc_ead_home_courses_certifications_true jwc_ead_certification' id='{$enrollment_id}'>SOLICITAR CERTIFICADO</div>";
   						else:
   							echo "<a title='Salvar, Imprimir Certificado!' href='" . BASE . "/campus/imprimir/{$Read->getResult()[0]['certificate_key']}' class='icon-printer wc_ead_home_courses_certifications wc_ead_home_courses_certifications_print'>IMPRIMIR CERTIFICADO</a>";
   						endif;
   					endif;
   				endif;
   				?>
   				</article><?php
   			endforeach;
   		endif;

   		$Read->ExeRead(DB_EAD_ORDERS, "WHERE user_id = :user AND order_status = 'started' ORDER BY order_confirmation_purchase_date DESC, order_purchase_date DESC", "user={$Admin['user_id']}");
   		if ($Read->getResult()):
   			?>
   			<section>
   				<header class="wc_ead_home_header" style="margin-top: 30px; font-size: 0.8em;">
   					<h2 class="icon-cart">Pedidos em Aberto:</h2>
   					<p>Pedidos aguardando confirmação de compra!</p>
   				</header>
   				<?php
   				foreach ($Read->getResult() as $StudentOrders):
   					$StudentOrders['order_currency'] = ($StudentOrders['order_currency'] ? $StudentOrders['order_currency'] : "BRL");

   					$Read->FullRead("SELECT course_title FROM " . DB_EAD_COURSES . " WHERE course_id = :course", "course={$StudentOrders['course_id']}");
   					$CourseTitle = ($Read->getResult() ? "Curso {$Read->getResult()[0]['course_title']}" : "Produto #{$StudentOrders['order_product_id']} na Hotmart");
   				?><article class="wc_ead_studend_orders">
   					<h1 class="row">
   						<?= $CourseTitle; ?>
   					</h1>
   					<p class="row">
   						<?= date("d/m/Y H\hi", strtotime($StudentOrders['order_purchase_date'])); ?>
   					</p>
   					<p class="row row_pay">
   						<span>$ <?= number_format($StudentOrders['order_price'], '2', ',', '.'); ?> (<?= $StudentOrders['order_currency']; ?>)</span> <img width="25" src="<?= BASE; ?>/_cdn/bootcss/images/pay_<?= $StudentOrders['order_payment_type']; ?>.png" alt="<?= $StudentOrders['order_payment_type']; ?>" title="<?= $StudentOrders['order_payment_type']; ?>" />
   					</p>
   					<p class="row">
   						<span class="radius bar_<?= getWcHotmartStatusClass($StudentOrders['order_status']) ?>" id="<?= $StudentOrders['order_id']; ?>"><?= getWcHotmartStatus($StudentOrders['order_status']); ?></span>
   					</p>
   					</article><?php
   				endforeach;
   				?>
   			</section>
   			<?php
   		endif;

                        //READ BONUS
   		$Read->FullRead(
   			"SELECT "
   			. "b.course_id,"
   			. "b.bonus_course_id,"
   			. "b.bonus_wait,"
   			. "c.course_title,"
   			. "c.course_name,"
   			. "c.course_cover,"
   			. "e.enrollment_id,"
   			. "e.enrollment_start,"
   			. "e.enrollment_end "
   			. "FROM " . DB_EAD_COURSES_BONUS . " b "
   			. "INNER JOIN " . DB_EAD_COURSES . " c ON b.bonus_course_id = c.course_id "
   			. "LEFT JOIN " . DB_EAD_ENROLLMENTS . " e ON (b.course_id = e.course_id AND e.user_id = :user) "
   			. "WHERE b.course_id IN(SELECT course_id FROM " . DB_EAD_ENROLLMENTS . " WHERE user_id = :user) "
   			. "AND (b.bonus_course_id NOT IN(SELECT course_id FROM " . DB_EAD_ENROLLMENTS . " WHERE user_id = :user) "
   				. "OR e.enrollment_end > (SELECT enrollment_end FROM " . DB_EAD_ENROLLMENTS . " WHERE user_id = :user AND course_id = b.bonus_course_id) ) "
   			. "AND CASE WHEN b.bonus_ever = 2 THEN e.enrollment_start >= b.bonus_ever_date ELSE 1 = 1 END "
   			. "ORDER BY e.enrollment_end DESC, b.bonus_wait ASC",
   			"user={$user_id}"
   		);

                        //SHOW BONUS
   		$ShowBonus = null;
   		if ($Read->getResult()):
   			$EnrollmentUpdateKey = array();
   			foreach ($Read->getResult() as $Bonus):
   				$Read->FullRead(
   					"SELECT "
   					. "e.enrollment_id "
   					. "FROM " . DB_EAD_ENROLLMENTS . " e "
   					. "WHERE e.user_id = :user "
   					. "AND e.course_id = :course",
   					"user={$user_id}&course={$Bonus['bonus_course_id']}"
   				);

   				if ($Read->getResult()):
   					if (!in_array($Bonus['course_title'], $EnrollmentUpdateKey)):
   						$EnrollmentUpdateKey[] = $Bonus['course_title'];
   						$UpdateBonus = ['enrollment_bonus' => $Bonus['enrollment_id'], 'enrollment_end' => $Bonus['enrollment_end']];
   						$Update->ExeUpdate(DB_EAD_ENROLLMENTS, $UpdateBonus, "WHERE user_id = :user AND enrollment_id = :enroll", "user={$user_id}&enroll={$Read->getResult()[0]['enrollment_id']}");
   					endif;
   				else:
   					$Bonus['course_cover'] = (file_exists("uploads/{$Bonus['course_cover']}") && !is_dir("uploads/{$Bonus['course_cover']}") ? "uploads/{$Bonus['course_cover']}" : 'admin/_img/no_image.jpg');

                                    //FREE DAYS || TIME
   					$DayThis = new DateTime(date("Y-m-d H:i:s"));
   					$DayPlay = new DateTime($Bonus['enrollment_start'] . "+{$Bonus['bonus_wait']}days");
   					$BonusDiff = $DayThis->diff($DayPlay);

   					if (($BonusDiff->h <= 1 || $BonusDiff->invert) && empty($EnrollmentFree)):
   						$EnrollmentFree = true;

   					$CreateUserBonus = ['user_id' => $user_id, 'course_id' => $Bonus['bonus_course_id'], 'enrollment_bonus' => $Bonus['enrollment_id'], 'enrollment_start' => date('Y-m-d H:i:s'), 'enrollment_end' => $Bonus['enrollment_end']];
   					$Create->ExeCreate(DB_EAD_ENROLLMENTS, $CreateUserBonus);

   					echo "<div class='wc_ead_win'>"
   					. "<div class='wc_ead_win_box'>"
   					. "<img src='" . BASE . "/tim.php?src={$Bonus['course_cover']}&w=" . round(IMAGE_W / 2) . "&h=" . round(IMAGE_H / 2) . "' alt='{$Bonus['course_title']}' title='{$Bonus['course_title']}'/>"
   					. "<div class='wc_ead_win_box_content al_center'>"
   					. "<p class='title icon-heart'>Uoolll {$user_name} :)</p>"
   					. "<p>O curso <b>{$Bonus['course_title']}</b> acaba de ser liberado como bônus em sua conta, e você já pode iniciar seus estudos!</p>"
   					. "<a title='Ver Curso Agora!' href='" . BASE . "/campus/curso/{$Bonus['course_name']}' class='btn btn_blue icon-lab'>Ver curso agora</a>"
   					. "<span title='Fechar Aviso!' class='m_left btn btn_red icon-cross jwc_ead_close_bonus'>Fechar</span>"
   					. "</div>"
   					. "</div>"
   					. "</div>";
   				else:
   					$ShowBonus .= "<article class='box box4'>"
   					. "<img alt='{$Bonus['course_title']}' title='{$Bonus['course_title']}' src='" . BASE . "/tim.php?src={$Bonus['course_cover']}&w=" . IMAGE_W / 3 . "&h=" . IMAGE_H / 3 . "'/>"
   					. "<div style='text-align: center;'>"
   					. " <h1 style='margin: 0; padding: 10px; font-weight: 500; font-size: 0.65em; background: #333; color: #fff;'><span class='icon-lock wc_tooltip'><span class='wc_tooltip_balloon'>Curso {$Bonus['course_title']}</span>" . ($BonusDiff->days > 1 ? "Libera em {$BonusDiff->days} Dias" : ($BonusDiff->days == 1 ? "Libera em 1 Dia" : "Libera em {$BonusDiff->h} Horas")) . "</span></h1>"
   					. "</div>"
   					. "</article>";
   				endif;
   			endif;
   		endforeach;
   		if ($ShowBonus):
   			echo '<section>'
   			. '<header class="wc_ead_home_header" style="margin-top: 30px; font-size: 0.8em;">'
   			. '<h2 class="icon-rocket">Liberações Pendentes:</h2>'
   			. '<p>Confira bônus a serem liberados em sua conta!</p>'
   			. '</header>'
   			. $ShowBonus .
   			'</section>';
   		endif;
   	endif;
   	?>
   </section>
</div>