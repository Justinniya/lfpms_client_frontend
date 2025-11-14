<!-- Display the message for status 0 and the delete form -->
<div class="w3-margin card w3-white w3-xlarge w3-padding" id="container">
    <p>Assessment Declined. Submit a new assessment.</p>
    <p><b>Status:</b> Assessment Declined</p>
    <form method="POST" onsubmit="return confirm('Are you sure you want to delete the assessment with status 0?');">
        <button type="submit" class="w3-button w3-small w3-red">Submit new assessment</button>
    </form>
</div>