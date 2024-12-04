<div class="courses-container">
    <h5 class="text-md font-bold pt-4 pl-4">Clases Actuales</h5>
    <div class="courses pl-4 pr-4 pb-4" id="courseRunning">
        <div class="card card-course shadow">
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
        </div>
    </div>

    <h5 class="text-md font-bold pt-4 pl-4">Historial de clases</h5>
    <div id="scrollable">
    <div class="courses pl-4 pr-4 pb-4" id="courseHistory">
        <div class="card card-course shadow">
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
        </div>
    </div>
    </div>
</div>
<script>
const bgLightClasses = [
    "bg-red-50", "bg-red-100", "bg-orange-50", "bg-orange-100",
    "bg-yellow-50", "bg-yellow-100", "bg-green-50", "bg-green-100",
    "bg-blue-50", "bg-blue-100", "bg-gray-50", "bg-gray-100", "bg-gray-200"
];

const bgClasses = [
    "bg-blue", "bg-red", "bg-green", "bg-gray", "bg-orange-500",
    "bg-red-50", "bg-red-100", "bg-red-200", "bg-red-300", "bg-red-400",
    "bg-red-500", "bg-red-600", "bg-red-700", "bg-red-800", "bg-red-900",
    "bg-orange-50", "bg-orange-100", "bg-orange-200", "bg-orange-300", "bg-orange-400",
    "bg-orange-600", "bg-orange-700", "bg-orange-800", "bg-orange-900",
    "bg-yellow-50", "bg-yellow-100", "bg-yellow-200", "bg-yellow-300", "bg-yellow-400",
    "bg-yellow-500", "bg-yellow-600", "bg-yellow-700", "bg-yellow-800", "bg-yellow-900",
    "bg-green-50", "bg-green-100", "bg-green-200", "bg-green-300", "bg-green-400",
    "bg-green-500", "bg-green-600", "bg-green-700", "bg-green-800", "bg-green-900",
    "bg-blue-50", "bg-blue-100", "bg-blue-200", "bg-blue-300", "bg-blue-400",
    "bg-blue-500", "bg-blue-600", "bg-blue-700", "bg-blue-800", "bg-blue-900",
    "bg-gray-50", "bg-gray-100", "bg-gray-200", "bg-gray-300", "bg-gray-400",
    "bg-gray-500", "bg-gray-600", "bg-gray-700", "bg-gray-800", "bg-gray-900"
];

function getRandomBgClass() {
    return bgClasses[Math.floor(Math.random() * bgClasses.length)];
}

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
            const bgClass = getRandomBgClass();
                const textClass = bgLightClasses.includes(bgClass) ? "text-dark" : "text-white";
                courseRunning.innerHTML += `<a class="text-decoration-none" href="/views/class/index.php?section_id=${element.section_id}">
                    <div class="card card-course shadow">
                        <div class="p-0 card-bd flex flex-column">
                            <div class="name w-full p-2 ${bgClass}  ${textClass} mb-1">
                                ${element.class_code}
                            </div>
                            <div class="infoClass p-2">
                                <span class=" font-bold text-md mb-2">${element.hour_start} ${element.class_name}</span>
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
const courseHistory = document.getElementById('courseHistory');
fetch('/api/get/admin/classesHistory.php')
    .then((res)=>{return res.json()})
    .then((res)=> {
        courseHistory.innerHTML = '';
        res.forEach(element => {
            const bgClass = getRandomBgClass();
                const textClass = bgLightClasses.includes(bgClass) ? "text-dark" : "text-white";
                courseHistory.innerHTML += `<a class="text-decoration-none" href="/views/class/index.php?section_id=${element.section_id}">
                <div class="card card-course shadow">
                    <div class="p-0 card-bd flex flex-column">
                        <div class="name w-full font-bold p-2 ${bgClass}  ${textClass} mb-1">
                            ${element.class_code}
                        </div>
                        <div class="infoClass p-2">
                            <span class=" mb-2"><span class="text-md">${element.hour_start}</span> ${element.class_name}</span>
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
</script>