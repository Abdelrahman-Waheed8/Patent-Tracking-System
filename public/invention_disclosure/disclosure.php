<?php
include "../../src/config/config_session.php";
include "../../src/view/disclosureView.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="disclosure.css">
    <script src="disclosure.js"></script>

    <title>IP System</title>
</head>

<body>

    <div class="container">

        <aside class="sidebar">
            <h2 class="logo">IP System</h2>

            <ul class="menu">
                <li><a href="../dashboard/dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="../inventors/inventors.php"><i class="fas fa-user-astronaut"></i>inventors</a></li>
                <li><a href="./disclosure.php" class="active"><i class="fas fa-lightbulb"></i> Disclosure</a></li>
                <li><a href="../patent/patent.php"><i class="fas fa-file"></i> Patents</a></li>
                <li><a href="../renewals/renewals.php"><i class="fas fa-sync"></i> Renewals</a></li>
                <li><a href="../licensing/licensing.php"><i class="fas fa-handshake"></i> Licensing</a></li>
            </ul>

            <a class="logout-btn" href="../index.php">
                <button class="logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </a>
        </aside>

        <main class="page_structure">

            <nav class="panel-header">
                <div class="left">
                    <i class="fas fa-list icon-btn" onclick="toggleSidebar()"></i>
                    <h6>Invention Disclosure</h6>
                </div>

                <div class="right">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search...">
                    </div>

                    <i class="fas fa-bell icon-btn"></i>

                    <div class="user icon-btn">
                        <i class="fas fa-user"></i>
                        <i class="fas fa-chevron-down small"></i>
                    </div>
                </div>
            </nav>

            <section class="content">

                <form action="../../src/disclosure.php" class="disclosure-form" enctype="multipart/form-data"
                    method="POST">

                    <h2>Invention Disclosure</h2>

                    <div class="input-group">
                        <label>Invention Title</label>
                        <input type="text" name="title">
                    </div>

                    <div class="input-group">
                        <label>Description</label>
                        <textarea rows="6" name="description"></textarea>

                        <label>Existing Patent References (Optional)</label>
                        <input type="text" name="prior_art_link" placeholder="e.g. US9876543A">

                        <label>Existing Patent References Description (Optional)</label>
                        <input type="text" name="prior_art_desc">
                    </div>

                    <div
                        style="margin-top: 20px; padding: 25px; border: 1px solid #e0e0e0; border-radius: 12px; background-color: #f9f9f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; box-shadow: 0 4px 6px rgba(0,0,0,0.05); max-width: 500px;">

                        <div class="input-group" style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 10px; color: #333; font-size: 16px;"><b>Disclosure
                                    Scope:</b></label>

                            <div style="display: flex; gap: 20px; align-items: center;">
                                <label style="cursor: pointer; display: flex; align-items: center; gap: 5px;">
                                    <input type="radio" id="is_national" name="jurisdictional_type" value="national"
                                        onclick="toggleFields()" style="accent-color: teal; transform: scale(1.1);">
                                    National
                                </label>

                                <label style="cursor: pointer; display: flex; align-items: center; gap: 5px;">
                                    <input type="radio" id="is_international" name="jurisdictional_type"
                                        value="international" onclick="toggleFields()"
                                        style="accent-color: teal; transform: scale(1.1);"> International
                                </label>
                            </div>
                        </div>

                        <div id="national_section"
                            style="display: none; margin-top: 20px; padding: 15px; background: #fff; border-radius: 8px; border-left: 4px solid teal;">
                            <label for="country" style="display: block; margin-bottom: 8px; font-weight: 600;">Select
                                Country:</label>
                            <select name="scope" id="country"
                                style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc; outline: none;">
                                <option value="Afghanistan">Afghanistan</option>
                                <option value="Albania">Albania</option>
                                <option value="Algeria">Algeria</option>
                                <option value="Andorra">Andorra</option>
                                <option value="Angola">Angola</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Armenia">Armenia</option>
                                <option value="Australia">Australia</option>
                                <option value="Austria">Austria</option>
                                <option value="Azerbaijan">Azerbaijan</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="Bangladesh">Bangladesh</option>
                                <option value="Belgium">Belgium</option>
                                <option value="Bolivia">Bolivia</option>
                                <option value="Brazil">Brazil</option>
                                <option value="Bulgaria">Bulgaria</option>
                                <option value="Cambodia">Cambodia</option>
                                <option value="Cameroon">Cameroon</option>
                                <option value="Canada">Canada</option>
                                <option value="Chile">Chile</option>
                                <option value="China">China</option>
                                <option value="Colombia">Colombia</option>
                                <option value="Croatia">Croatia</option>
                                <option value="Cuba">Cuba</option>
                                <option value="Cyprus">Cyprus</option>
                                <option value="Denmark">Denmark</option>
                                <option value="Egypt">Egypt</option>
                                <option value="Finland">Finland</option>
                                <option value="France">France</option>
                                <option value="Germany">Germany</option>
                                <option value="Greece">Greece</option>
                                <option value="Hungary">Hungary</option>
                                <option value="Iceland">Iceland</option>
                                <option value="India">India</option>
                                <option value="Indonesia">Indonesia</option>
                                <option value="Iran">Iran</option>
                                <option value="Iraq">Iraq</option>
                                <option value="Ireland">Ireland</option>
                                <option value="Italy">Italy</option>
                                <option value="Japan">Japan</option>
                                <option value="Jordan">Jordan</option>
                                <option value="Kenya">Kenya</option>
                                <option value="Kuwait">Kuwait</option>
                                <option value="Lebanon">Lebanon</option>
                                <option value="Libya">Libya</option>
                                <option value="Malaysia">Malaysia</option>
                                <option value="Mexico">Mexico</option>
                                <option value="Morocco">Morocco</option>
                                <option value="Netherlands">Netherlands</option>
                                <option value="New Zealand">New Zealand</option>
                                <option value="Nigeria">Nigeria</option>
                                <option value="Norway">Norway</option>
                                <option value="Oman">Oman</option>
                                <option value="Pakistan">Pakistan</option>
                                <option value="Palestine">Palestine</option>
                                <option value="Peru">Peru</option>
                                <option value="Philippines">Philippines</option>
                                <option value="Poland">Poland</option>
                                <option value="Portugal">Portugal</option>
                                <option value="Qatar">Qatar</option>
                                <option value="Russia">Russia</option>
                                <option value="Saudi Arabia">Saudi Arabia</option>
                                <option value="Singapore">Singapore</option>
                                <option value="South Africa">South Africa</option>
                                <option value="South Korea">South Korea</option>
                                <option value="Spain">Spain</option>
                                <option value="Sudan">Sudan</option>
                                <option value="Sweden">Sweden</option>
                                <option value="Switzerland">Switzerland</option>
                                <option value="Syria">Syria</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Tunisia">Tunisia</option>
                                <option value="Turkey">Turkey</option>
                                <option value="Ukraine">Ukraine</option>
                                <option value="United Arab Emirates">United Arab Emirates</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="United States">United States</option>
                                <option value="Vietnam">Vietnam</option>
                                <option value="Yemen">Yemen</option>
                            </select>
                        </div>

                        <div id="international_section"
                            style="display: none; margin-top: 20px; padding: 15px; background: #fff; border-radius: 8px; border-left: 4px solid teal;">
                            <label style="display: block; margin-bottom: 10px; font-weight: 600;">Select
                                Regions:</label>

                            <label
                                style="display: block; margin-bottom: 10px; cursor: pointer; padding: 5px; background: #f0fdf4; border-radius: 4px;">
                                <input type="checkbox" name="world" value="world" style="accent-color: teal;"> <b>Whole
                                    World</b>
                            </label>

                            <p style="margin: 10px 0 5px; font-size: 0.9em; color: #666;">Or select Continents:</p>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                <label style="cursor: pointer;"><input type="checkbox" name="continents[]"
                                        value="africa"> Africa</label>
                                <label style="cursor: pointer;"><input type="checkbox" name="continents[]"
                                        value="europe"> Europe</label>
                                <label style="cursor: pointer;"><input type="checkbox" name="continents[]" value="asia">
                                    Asia</label>
                                <label style="cursor: pointer;"><input type="checkbox" name="continents[]"
                                        value="north_america"> North America</label>
                                <label style="cursor: pointer;"><input type="checkbox" name="continents[]"
                                        value="south_america"> South America</label>
                            </div>
                        </div>
                    </div>

                    <br>



                    <div class="contributors-section">
                        <h3>Contributors</h3>

                        <div id="contributors-list">
                            <div class="contributor-row" style="display:flex; gap:10px; margin-bottom:10px;">
                                <input type="text" name="ContributorIDs[]" placeholder="Contributor ID"
                                    style="flex:2; padding:5px;">

                                <input type="text" name="contributionPercentages[]" placeholder="%"
                                    style="flex:1; padding:5px;">

                                <input type="text" name="companyNames[]" placeholder="External Company (optional)"
                                    style="flex:2; padding:5px;">

                                <button type="button" onclick="removeRow(this)">-</button>
                            </div>
                        </div>

                        <button type="button" onclick="addContributor()" class="submit-btn">
                            + Add Contributor
                        </button>
                    </div>

                    <div class="upload-container">
                        <label class="upload-box" id="uploadBox">
                            <input type="file" id="fileInput" name="files[]" multiple hidden>
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span id="uploadText">Upload Files</span>
                        </label>
                    </div>

                    <?php
                    $disclosureView = new disclosureView();
                    $disclosureView->displaySessionInfo();
                    ?>

                    <div class="form-footer">
                        <button type="submit" class="submit-btn">Submit</button>
                    </div>

                </form>

            </section>

        </main>

    </div>


</body>

</html>