<div class="col-md-12">
   <!-- slider start here -->
   <section id="prescription">
      <div class="container px-4">
        <header class="header">
          <p class="mb-0 pt-2 fs-"><b>Location :</b> Ukhia Upazila</p>
          @foreach($prescriptionCreation as $pc)
          <h4 class="mb-0 p-3 text-center">Prescription {{ $pc->PrescriptionId }}</h4>
          @endforeach
        </header>
        <div class="topHeading border border-start-0 border-end-0 py-2">
          @foreach($patientDetails as $patientDetail)
          <span class="me-3"><b>Name :</b> {{ $patientDetail->GivenName }} {{ $patientDetail->FamilyName }}</span>
          <span class="me-3"><b>Age :</b> {{ $patientDetail->Age }}</span>
          <span class="me-3"><b>Gender :</b> {{ $patientDetail->Gender->GenderCode }}</span>
          @endforeach
          <span class="me-3"><b>Date :</b> {{ date('d-m-Y', strtotime($pc->CreateDate)) }}</span>
          
        </div>

        <div class="d-flex">
          <aside class="aside">
            <div class="item pt-3">
              <b class="d-block mb-0 py-2 border-bottom">Complaints</b>
              @foreach($Complaints as $Complaint)
              <p class="mb-0 mt-2 pe-2">{{ date('d-m-Y', strtotime($Complaint->CreateDate)) }}: {{ $Complaint->ChiefComplain }} for {{ $Complaint->CCDurationValue }} {{ $Complaint->DurationInEnglish }}</p>
              @endforeach
            </div>
            <div class="item pt-3">
              <b class="d-block mb-0 py-2 border-bottom">O/E</b>
              @foreach($HeightWeight as $hw)
              <p class="mb-0 mt-2 pe-2">Height: {{ $hw->Height }} cm</p>
              <p class="mb-0 mt-2 pe-2">Weight: {{ $hw->Weight }} kg</p>
              <p class="mb-0 mt-2 pe-2">BMI: {{ $hw->BMI }}</p>
              @endforeach
              @foreach($BP as $bps)
              <p class="mb-0 mt-2 pe-2">Pulse: {{$bps->HeartRate}}</p>
              <p class="mb-0 mt-2 pe-2">Blood Pressure: {{$bps->Blood Pressure}} mmHg</p>
              @endforeach
              @foreach($GlucoseHb as $GHB)
              <p class="mb-0 mt-2 pe-2">RBG: {{$GHB->RBG}} mMol</p>
              <p class="mb-0 mt-2 pe-2">FBG: {{$GHB->FBG}} mMol</p>
              <p class="mb-0 mt-2 pe-2">Hemoglobin: {{$GHB->Hemoglobin}} g/dL</p>
              @endforeach
            </div>
            <div class="item pt-3">
              <b class="d-block mb-0 py-2 border-bottom">Provisional Dx</b>
              <p class="mb-0 mt-2  pe-2">Date: 12-10-2022</p>
              <p class="mb-0 mt-2  pe-2">1. R509 Fever, unspecified [Confirmed]</p>
              <p class="mb-0 mt-2  pe-2">
                2. 110 Essential (primary) hypertension [Presumptive]
              </p>
            </div>
            <div class="item pt-3">
              <b class="d-block mb-0 py-2 border-bottom"
                >On Spot Investigations</b
              >
              <p class="mb-0 mt-2 pe-2">RBS: 9.5 mMol</p>
              <p class="mb-0 mt-2 pe-2">FBS: 0.0 mMol</p>
              <p class="mb-0 mt-2 pe-2">Hemoglobin: 11.0 g/dL VIA/Negative</p>
            </div>
            <div class="item pt-3">
              <b class="d-block mb-0 py-2 border-bottom">Lab Investigations</b>
              <p class="mb-0 mt-2 pe-2">CBC with ESR</p>
              <p class="mb-0 mt-2 pe-2">Widal Test</p>
              <p class="mb-0 mt-2 pe-2">Creatinine</p>
            </div>
          </aside>
          <div class="rightSide position-relative w-100 py-3 px-4">
            <h2 class="mb-4">℞</h2>
            <div class="medicine mb-4">
              <p class="mb-0"><b>1.</b> Tab Napa (500mg)</p>
              <i>৬ ঘন্টা পর পর - If fever</i>
            </div>
            <div class="medicine mb-4">
              <p class="mb-0"><b>1.</b> Tab Napa (500mg)</p>
              <i>৬ ঘন্টা পর পর - If fever</i>
            </div>

            <div class="nextinfo">
              <div class="medicine mb-4">
                <p class="mb-0"><b>Follow-up / পরবর্তী সাক্ষাৎ</b></p>
                <p class="mb-0"><b>1. </b>Status: Static</p>
              </div>
              <div class="medicine mb-4">
                <p class="mb-0"><b>Advice / পরামর্শ</b></p>
                <p class="mb-0"><b>1. </b>Take more water</p>
              </div>
              <div class="medicine mb-4">
                <p class="mb-0"><b>Referral / রেফারেল</b></p>
                <p class="mb-0"><b>1. </b>12/10/2023: Hypertension, BRAC</p>
              </div>
            </div>

            <div class="signatureBox text-center">
              <img
                src="assets/img/signature.png"
                alt="img"
                class="signatureImage"
              />
              <p class="mb-0">Jiaur Rahman</p>
              <i class="my-0">MBBS, MPH, MS(Orthopedics)</i>
            </div>
          </div>
        </div>

        <footer class="footer d-flex justify-content-between">
          <address class="mb-0">
            <p class="mb-0">Haefa USA</p>
            <p class="mb-0">311 Bedford St, Lexington MA 07420, USA</p>
            <p class="mb-0">Email: healthonwheels.usa@gmail.com</p>
            <p class="mb-0">Website: www.healthonwheels.usa.org</p>
          </address>
          <address class="mb-0">
            <p class="mb-0">Haefa Bangladesh</p>
            <p class="mb-0">House: 31, Road: 16 Sector: 13 Uttara</p>
            <p class="mb-0">Email: healthonwheels.usa@gmail.com</p>
            <p class="mb-0">Website: www.healthonwheels.usa.org</p>
          </address>
        </footer>
        <p class="mb-0 text-center pb-4 logoText">
          Powered By:
          <img src="assets/img/apilogo.png" alt="img" class="apiLogo" />
        </p>
      </div>
    </section>
    <!-- slider end here -->
</div>