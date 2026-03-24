<?php
$tel_text = carbon_get_theme_option('crb_tel_text');
$tel_link = carbon_get_theme_option('crb_tel_link');
$tel_img = carbon_get_theme_option('crb_tel_img');

$email_text = carbon_get_theme_option('crb_email_text');
$email_link = carbon_get_theme_option('crb_email_link');
$email_img = carbon_get_theme_option('crb_email_img');
?>

<div class="toggle-contacts__bar">
    <div class="toggle-contacts__list">

        <?php if (!empty($tel_link)) :
            $tel_img_url = wp_get_attachment_image_url($tel_img, 'full');
        ?>
            <div class="toggle-contacts__list__item">

                <a href="<?php echo esc_url($tel_link); ?>" class="toggle-contacts__list__item__link">
                    <span><?php echo esc_html($tel_text); ?></span>
                    <?php if ($tel_img_url) : ?>
                        <img src="<?php echo esc_url($tel_img_url); ?>" alt="<?php echo esc_attr($tel_text); ?>">
                    <?php endif; ?>

                </a>
            </div>
        <?php endif; ?>
        <?php if (!empty($email_link)) :
            $email_img_url = wp_get_attachment_image_url($email_img, 'full');
        ?>
            <div class="toggle-contacts__list__item">

                <a href="mailto:<?php echo esc_attr($email_link); ?>" class="toggle-contacts__list__item__link">
                    <span><?php echo esc_html($email_text); ?></span>
                    <?php if ($email_img_url) : ?>
                        <img src="<?php echo esc_url($email_img_url); ?>" alt="<?php echo esc_attr($email_text); ?>">
                    <?php endif; ?>

                </a>
            </div>
        <?php endif; ?>

        <?php
        $messengers = carbon_get_theme_option('crb_contacts');

        if (!empty($messengers)) :
            foreach ($messengers as $messenger) :
                // Получаем ссылку и изображение для каждого мессенджера
                $mes_link = isset($messenger['crb_contact_link']) ? $messenger['crb_contact_link'] : '';
                $mes_img_id = isset($messenger['crb_contact_image']) ? $messenger['crb_contact_image'] : '';
                $mes_img_url = $mes_img_id ? wp_get_attachment_image_url($mes_img_id, 'full') : '';
                $mes_text = isset($messenger['crb_contact_name']) ? $messenger['crb_contact_name'] : '';
        ?>
                <div class="toggle-contacts__list__item">

                    <?php if ($mes_link) : ?>

                        <a class="toggle-contacts__list__item__link" href="<?php echo esc_url($mes_link); ?>">
                            <span><?php echo esc_html($mes_text); ?></span>
                            <?php if ($mes_img_url) : ?>
                                <img src="<?php echo esc_url($mes_img_url); ?>" alt="">
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                </div>
        <?php
            endforeach;
        endif;
        ?>
    </div>

    <a href="#" class="toggle-contacts-icon">
        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 11C20 6.02928 15.9707 2 11 2C6.0293 2 2.00001 6.02929 2.00001 11C1.9972 13.301 2.87921 15.5162 4.4629 17.1855L4.96778 17.7178L3.69923 20H11V21H2.00001L3.73731 17.874C1.97707 16.0186 0.996886 13.5576 1.00001 11C1.00001 5.477 5.47701 1 11 1C16.523 1 21 5.477 21 11C21 16.523 16.523 21 11 21V20C15.9707 20 20 15.9707 20 11Z" fill="#242424" />
            <path d="M20 11C20 6.02928 15.9707 2 11 2C6.0293 2 2.00001 6.02929 2.00001 11C1.9972 13.301 2.87921 15.5162 4.4629 17.1855L4.96778 17.7178L3.69923 20H11C15.9707 20 20 15.9707 20 11ZM13 12V14H7.00001V12H13ZM15 8V10H7.00001V8H15ZM22 11C22 17.0753 17.0753 22 11 22H0.300789L2.52052 18.0039C0.894881 16.0395 -0.00306639 13.5643 7.8678e-06 10.999C0.00053558 4.92419 4.92505 4.12279e-06 11 0C17.0753 0 22 4.92472 22 11Z" fill="#242424" />
        </svg>

    </a>
</div>