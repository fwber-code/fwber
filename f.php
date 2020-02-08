<?php
/*
    Copyright 2020 FWBer.com

    This file is part of FWBer.

    FWBer is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    FWBer is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero Public License for more details.

    You should have received a copy of the GNU Affero Public License
    along with FWBer.  If not, see <https://www.gnu.org/licenses/>.
*/
?>

</div>
</main>

<footer class="footer mt-auto py-5">
    <div class="container">
    <div class="row">
        <div class="col-12 col-md">
            <?php echo getSiteName();?>
            <small class="d-block mb-3 text-muted">&copy; 2019</small>
        </div>
        <div class="col-6 col-md">
            <ul class="list-unstyled text-small">
                <li><a class="text-muted" href="/join">Join Now</a></li>
                <li><a class="text-muted" href="/signin">Sign In</a></li>
            </ul>
        </div>
        <div class="col-6 col-md">
            <ul class="list-unstyled text-small">
                <li><a class="text-muted" href="https://github.com/fwber-code/fwber">Source Code on GitHub</a></li>
                <li><a class="text-muted" href="/tos">Terms Of Service</a></li>
                <li><a class="text-muted" href="/privacy">Privacy Policy</a></li>
                <li><a class="text-muted" href="/contact">Contact Us</a></li>
            </ul>
        </div>
    </div>
    </div>
</footer>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-32334329-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

<script type="text/javascript">
    function toggle(obj)
    {
        obj.parentNode.style.backgroundColor = obj.checked ? "#cfc" : "#fff";
    }

    function toggleAll()
    {
        var cb = document.getElementsByTagName("input");
        for (var i = 0; i < cb.length; i++) {
            if (cb[i].type != "checkbox") continue;
            toggle(cb[i]);
        }
    }

    toggleAll();
</script>