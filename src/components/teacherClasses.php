

<h5 class="text-md font-bold pt-4 pl-4">Running Course</h5>
          <div class="courses pl-4 pr-4 pb-4" id="courseRunning">
              
              
          </div>
<script>

function getClassesView() {
    const courseRunning = document.getElementById('courseRunning');
    courseRunning.innerHTML = `<div class="card card-course shadow">
                <div class="p-0 card-bd flex flex-column">
                    <div class="name w-full p-2 bg-secondary text-white mb-1">
                        <p class="card-text placeholder-glow">
                            <span class="placeholder col-4"></span>
                        </p>
                    </div>
                    <div class="infoClass p-3">
                        <p class="card-text placeholder-glow">
                            <span class="placeholder bg-secondary col-12"></span>
                            <span class="placeholder bg-secondary col-4"></span>
                            <span class="placeholder bg-secondary col-6"></span>
                            <span class="placeholder bg-secondary col-8"></span>
                        </p>
                        
                    </div>
                </div>
            </div>`;
    fetch('/api/get/admin/classesAssigned.php')
    .then((res)=>{return res.json()})
    .then((res)=> {
        courseRunning.innerHTML = '';
        res.forEach(element => {
                courseRunning.innerHTML += `<a class="text-decoration-none" href="/views/class/index.php?section_id=${element.section_id}">
                <div class="card card-course shadow">
                    <div class="p-0 card-bd flex flex-column">
                        <div class="name w-full p-2 bg-primary text-white mb-1">
                            ${element.class_code}
                            <small class="font-light text-xs">${element.hour_start}</small>
                        </div>
                        <div class="infoClass p-2">
                            <span class=" font-bold text-md mb-2">${element.class_name}</span>
                            <div class="pr mt-3">
                                <div class="progress mt-2">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                    </div>
                                <div class="text-end"><small class="font-light text-xs">Progress (0%)</small></div>
                            </div>
                        </div>
                    </div>
                </div></a>`;
            
        });
    })
}
getClassesView();
</script>