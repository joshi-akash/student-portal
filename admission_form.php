<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form | Doon University</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #1e3a8a; --primary-light: #3b82f6; --bg: #f3f4f6; --surface: #ffffff; --text: #1f2937; --muted: #6b7280; --border: #e5e7eb; }
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: var(--bg); color: var(--text); padding: 40px 20px; margin: 0; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .form-wrapper { max-width: 850px; margin: 0 auto; background: var(--surface); padding: 50px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-top: 6px solid var(--primary); animation: fadeUp 0.6s ease forwards; }
        .header { margin-bottom: 40px; text-align: center; }
        .header h1 { font-size: 28px; color: var(--primary); font-weight: 600; margin: 0 0 10px 0; }
        .header p { color: var(--muted); font-size: 15px; margin: 0; }
        .section-title { font-size: 18px; font-weight: 600; margin: 40px 0 20px; padding-bottom: 10px; border-bottom: 2px solid var(--bg); color: var(--primary); }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .full { grid-column: 1 / -1; }
        label { display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px; }
        input, select, textarea { width: 100%; padding: 14px; border: 2px solid var(--border); border-radius: 10px; background: #fafafa; font-size: 14px; transition: all 0.3s ease; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: var(--primary-light); background: var(--surface); box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
        .conditional { max-height: 0; opacity: 0; overflow: hidden; transition: all 0.5s; background: #f8fafc; border-radius: 12px; }
        .conditional.active { max-height: 500px; opacity: 1; padding: 24px; border: 1px solid var(--border); margin-top: 20px; }
        .btn-submit { width: 100%; padding: 18px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; margin-top: 40px; cursor: pointer; transition: all 0.3s; }
        .btn-submit:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(30, 58, 138, 0.3); }
        @media (max-width: 600px) { .grid { grid-template-columns: 1fr; } .form-wrapper { padding: 30px 20px; } }
    </style>
</head>
<body>
<div class="form-wrapper">
    <div class="header">
        <h1>Undergraduate Application</h1>
        <p>Complete your details below. You will use the password created here to log into the student portal later.</p>
    </div>
    <form action="submit.php" method="POST">
        <h3 class="section-title">👤 Personal Information</h3>
        <div class="grid">
            <div><label>Full Name</label><input type="text" name="name" required></div>
            <div><label>Mobile Number</label><input type="text" name="phone" required></div>
            <div><label>Date of Birth</label><input type="date" name="dob" required></div>
            <div>
                <label>Blood Group</label>
                <select name="blood_group" required>
                    <option value="" disabled selected>Select Group...</option>
                    <option value="A+">A+</option><option value="A-">A-</option>
                    <option value="B+">B+</option><option value="B-">B-</option>
                    <option value="O+">O+</option><option value="O-">O-</option>
                    <option value="AB+">AB+</option><option value="AB-">AB-</option>
                </select>
            </div>
            <div>
                <label>Interested Course</label>
                <select name="course" required>
                    <option value="" disabled selected>Select Course...</option>
                    <option value="B.Tech Computer Science">B.Tech Computer Science</option>
                    <option value="BBA">BBA</option>
                    <option value="MBA">MBA</option>
                    <option value="B.Sc Physics">B.Sc Physics</option>
                    <option value="BA English">BA English</option>
                </select>
            </div>
            <div><label>Portal Password</label><input type="password" name="password" placeholder="Create a strong password" required></div>
        </div>
        <h3 class="section-title">📚 Academic History</h3>
        <div class="grid">
            <div><label>10th Percentage</label><input type="number" step="0.01" name="tenth" required></div>
            <div><label>12th Percentage</label><input type="number" step="0.01" name="twelfth" required></div>
            <div>
                <label>Background (Stream)</label>
                <select name="background" required>
                    <option value="" disabled selected>Select Stream...</option>
                    <option value="Science (PCM)">Science (PCM)</option>
                    <option value="Science (PCB)">Science (PCB)</option>
                    <option value="Commerce">Commerce</option>
                    <option value="Humanities/Arts">Humanities/Arts</option>
                </select>
            </div>
            <div><label>12th Passing Year</label><input type="number" name="year" required></div>
        </div>
        <h3 class="section-title">👨‍👩‍👧 Family Details</h3>
        <div class="grid">
            <div><label>Father's Name</label><input type="text" name="father_name" required></div>
            <div><label>Mother's Name</label><input type="text" name="mother_name" required></div>
            <div><label>Father's Occupation</label><input type="text" name="father_occupation"></div>
            <div><label>Father's Qualification</label><input type="text" name="father_qualification"></div>
            <div><label>Annual Family Income (₹)</label><input type="number" name="income" required></div>
            <div class="full"><label>Permanent Address</label><textarea name="address" rows="2" required></textarea></div>
        </div>
        <h3 class="section-title">🏫 Sibling Information</h3>
        <div class="grid">
            <div class="full">
                <label>Do you have any siblings?</label>
                <select name="has_siblings" id="has_siblings" onchange="toggleSiblings()">
                    <option value="No">No</option><option value="Yes">Yes</option>
                </select>
            </div>
        </div>
        <div id="sibling_container" class="conditional">
            <div class="grid">
                <div><label>Number of Siblings</label><input type="number" name="num_siblings" id="num_siblings" value="0"></div>
                <div>
                    <label>Studying in Doon University?</label>
                    <select name="sibling_same_college" id="same_college" onchange="toggleCollegeDetails()">
                        <option value="No">No</option><option value="Yes">Yes</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="sibling_details" class="conditional">
            <div class="grid">
                <div><label>Sibling's Name</label><input type="text" name="sibling_name"></div>
                <div><label>Sibling's Roll Number</label><input type="text" name="sibling_roll_no"></div>
                <div class="full"><label>Course Enrolled In</label><input type="text" name="sibling_course"></div>
            </div>
        </div>
        <button type="submit" class="btn-submit">Submit Application Form</button>
    </form>
</div>
<script>
    function toggleSiblings() {
        const hasSib = document.getElementById('has_siblings').value === 'Yes';
        const sibContainer = document.getElementById('sibling_container');
        if (hasSib) { sibContainer.classList.add('active'); } 
        else { sibContainer.classList.remove('active'); document.getElementById('same_college').value = 'No'; toggleCollegeDetails(); }
    }
    function toggleCollegeDetails() {
        const sameCol = document.getElementById('same_college').value === 'Yes';
        const detailsContainer = document.getElementById('sibling_details');
        if (sameCol && document.getElementById('has_siblings').value === 'Yes') { detailsContainer.classList.add('active'); } 
        else { detailsContainer.classList.remove('active'); }
    }
</script>
</body>
</html>