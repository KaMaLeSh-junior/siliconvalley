function getalldatas() {
    let datas = new FormData();
    datas.append('data','getall');
    fetch('../server/events/render.php',{method:'POST',body:datas})
    .then(function(response) {
    return response.json();
    })
    .then(function(myJson) {
        document.querySelector('#renderedcomponent').innerHTML=myJson['photographs'];
        document.querySelector('#cameraman').innerHTML=myJson['username'];
        beforeafterrender();
    }).catch((error)=>{
        console.log(error);
      });
}
getalldatas()

document.querySelector('#cameraman_Datas').addEventListener('submit',function(e){
    e.preventDefault();
    let data = new FormData(this);
    fetch('../server/events/events.php',{method:"POST",body:data})
.then(function(response) {
return response.text();
})
.then(function(myJson) {
    if(myJson==200){
        e.target.reset();
        getalldatas()
    }
}).catch((error)=>{
    console.log(error);
  });
})

function sorting(type,data=""){
    let datas = new FormData();
    datas.append('data','sorting');
    datas.append('sorting',type);
    if(data){
    datas.append('cameraman',data);
    }
    fetch('../server/events/render.php',{method:'POST',body:datas})
    .then(function(response) {
    return response.json();
    })
    .then(function(myJson) {
        document.querySelector('#renderedcomponent').innerHTML="";
        document.querySelector('#renderedcomponent').innerHTML=myJson;beforeafterrender()
    }).catch((error)=>{
        console.log(error)
      });
}

document.querySelector('#cameramans').addEventListener('submit',function(e){
    e.preventDefault();
    let checkboxes = document.querySelectorAll('#selectedcamera');
    let selected = [];
    if(!checkboxes[0].checked){
        for (var i=0; i<checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                let values ="'"+checkboxes[i].value+"'";
                selected.push(values);
            }
        }
    }else{
        let values ="'"+checkboxes[0].value+"'";
                selected.push(values);
    }
    console.log(selected,checkboxes[0].value);
    sorting("username",selected)
})

function beforeafterrender(){
    document.querySelectorAll(".heart").forEach(elements=> {
        elements.addEventListener('click',function(e){
            let id = e.target.getAttribute('data-id');
            let like = e.target.getAttribute('data-like');
            let data = new FormData();
                data.append("data",'like');
                data.append("id",id);
                data.append("likes",like);
                fetch('../server/events/events.php',{method:"POST",body:data})
            .then(function(response) {
            return response.text();
            })
            .then(function(myJson) {
                    getalldatas()
            }).catch((error)=>{
                console.log(error);
              });
        })
    })
}

beforeafterrender()
