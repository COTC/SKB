<?php /* Template Name: Docusign Completed */ 

// Docusign is loaded in an iframe and once completed directs the user to a url of our choice 
// However, it is stuck in the iframe so to resolve that we direct Docusign here, then redirect 
// the parent to the aiq landing page, probably a better way but this will do for now

    $theme_settings = get_option('skb_theme_settings');
    $redirect_url = get_permalink( $theme_settings['approved_landing'] );

?>

<script type="text/javascript">
    function redirect_page() {
        window.top.location.href = "<?php echo $redirect_url; ?>"; 
    }
    redirect_page();
</script>