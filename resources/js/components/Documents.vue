<template>
  <div>
      <div class="col-sm-12 col-md-9" style="float:left;" >

            <div  v-if="searchByCompany== 'retencja'" class="card-header" style="text-align: center; font-size: 40px; background-color: #38c172">
              Retencja
            </div>
            <div  v-else-if="searchByCompany== 'biopro'" class="card-header" style="text-align: center; font-size: 40px; background-color: #6cb2eb">
              Biopro
            </div>
            <div  v-else class="card-header" style="text-align: center; font-size: 40px; background-color: #6c757d; color: #f8f9fa;">
              Wszystkie
            </div>


    <table class="table-bordered table-hover table-responsive" style="width: 100%; margin-left: 0;">
        <thead>
            <!-- FILTRY DOKUMENTÓW -->
            <tr>
              <th scope="col" ><input type="number" style="width: 40px;" v-model="searchById" placeholder="ID"/> </th>
              <th scope="col"><input type="text" style="width: 100px;" v-model="searchByName" placeholder="Nazwa"/></th>
              <th scope="col">
                <div class="radioPicker" style="width: 100px;" >
                <input type="radio" id="retencja" value="retencja" v-model="searchByCompany"/>
                <label for="retencja">Retencja</label>
                <br/>
                <input type="radio" id="biopro" value="biopro" v-model="searchByCompany"/>
                <label for="biopro">Biopro</label>
                <br/>
                <input type="radio" id="allCompany" value="" v-model="searchByCompany"/>
                <label for="allCompany">Wszystkie</label>
                </div>
              </th>
              <th scope="col">
                <div style="width: 90px;">
                <input type="radio" id="in" value="Przychodzący" v-model="searchByInOrOut"/>
                <label for="in">Przych.</label>
                <br/>
                <input type="radio" id="out" value="Wychodzący"  v-model="searchByInOrOut"/>
                <label for="out">Wych.</label>
                <br/>
                <input type="radio" id="allInOut" value="" v-model="searchByInOrOut"/>
                <label for="allInOut">Wszystkie</label>
                </div>
              </th>
              <th scope="col"><input type="text" style="width: 100px;" v-model="searchByCounterparty" placeholder="Kontrahent"/></th>
              <th scope="col">
              <input type="date" style="width: 120px;" v-model="searchByDateOfDoc" placeholder="Data dok."/>
              </th>
              <th scope="col">
                <select style="width: 100px;" v-model="searchByTypeOfDoc">
                <option disabled value="">Wybierz rodzaj dokumentu</option>
                <option value="">Wszystkie</option>
                <option>Faktura</option>
                <option>Umowa</option>
                <option>Inne</option>
                </select>
              </th>
              <th scope="col"><input type="text" style="width: 100px;" v-model="searchByNumberOnDoc" placeholder="Numer na dok."/></th>
              <th scope="col"><input type="text" style="width: 130px;" v-model="searchByDescription" placeholder="Opis"/></th>
              <th scope="col"><input type="text" style="width: 100px;" v-model="searchByStatus" placeholder="Status"/></th>
              <th scope="col"><input type="date" style="width: 150px;" v-model="searchByCreatedAt" placeholder="Data dodania"/></th>
            </tr>
            <!-- NAGŁÓWKI DOKUMENTÓW -->
            <tr>
            <th scope="col">#</th>
            <th scope="col">Nazwa</th>
            <th scope="col">Spółka</th>
            <th scope="col">Kierunek</th>
            <th scope="col">Kontrahent</th>
            <th scope="col">Data dok.</th>
            <th scope="col">Typ dok.</th>
            <th scope="col">Numer na dok.</th>
            <th scope="col">Opis</th>
            <th scope="col">Status</th>
            <th scope="col">Data dodania</th>
            </tr>
        </thead>
        <!-- TABELA DOKUMENTÓW -->
        <tbody v-for="document in filteredDocs" v-bind:key="document.id" >
            <tr v-if="(edit_id!=document.id)" >
            <th scope="row">{{document.id}}</th>
            <td>{{document.name}}</td>
            <td>{{document.company}}</td>
            <td>{{document.inOrOut}}</td>
            <td>{{document.counterparty}}</td>
            <td>{{document.dateOfDoc}}</td>
            <td>{{document.typeOfDoc}}</td>
            <td>{{document.numberOnDoc}}</td>
            <td >{{document.shortDesc}}</td>
            <td>{{document.status}}</td>
            <td>{{document.created_at}}</td>
            <td> <button class="btn btn-primary" @click="downloadFile(document.id, document.name)" > Pobierz </button> </td>

            <td> <button class="btn btn-secondary" @click="displayDoc(document.id)"> Podgląd </button></td>
            
            <td> <button class="btn btn-warning" @click="editDocument(document)" >Edytuj </button> </td>
            </tr>
            <!-- EDYCJA DOKUMENTÓW -->
            <tr v-if="(edit_id==document.id)" style="background-color: #f5d2de;" >
              <th scope="row">{{document.id}}</th>
              <td><input type="text" style="width: 100px;" placeholder="Nazwa" v-model="document.name" ></td>
              <td>{{document.company}}</td>
              <td>{{document.inOrOut}}</td>
              <td> <input type="text" style="width: 100px;" placeholder="Kontrahent" v-model="document.counterparty" > </td>
              <td>{{document.dateOfDoc}}</td>
              <td>{{document.typeOfDoc}}</td>
              <td>{{document.numberOnDoc}}</td>
              <td >{{document.shortDesc}}</td>
              <td>{{document.status}}</td>
              <td>{{document.created_at}}</td>
              <td> <button class="btn btn-success" @click="addDocument(document)" >Zapisz </button> </td>
              <td> <button class="btn btn-danger" @click="deleteDocument(document.id)"> Usuń </button> </td>
              <td> <button class="btn btn-dark" @click="cancelEdit()" >Anuluj </button> </td>
              <td></td>
            </tr>
        </tbody>
    </table>

    <!-- STRONY DOKUMENTÓW ? -->
    <nav aria-label="Page navigation example">
      <ul class="pagination">
        <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchDocuments(pagination.prev_page_url)">Poprzednia</a></li>

        <li class="page-item disabled"><a class="page-link text-dark" href="#">Strona {{ pagination.current_page }} z {{ pagination.last_page }}</a></li>
        
        <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchDocuments(pagination.next_page_url)">Następna</a></li>
      </ul>     
    </nav>

    </div>
  </div>
</template>
<!-- -->
<script>
    export default {
        data(){
            return {
                searchById:'',
                searchByName:'',
                searchByCompany:'',
                searchByInOrOut:'',
                searchByCounterparty:'',
                searchByDateOfDoc:'',
                searchByTypeOfDoc:'',
                searchByNumberOnDoc:'',
                searchByDescription:'',
                searchByStatus:'',
                searchByCreatedAt:'',
                documents: [],
                document:{
                    id:'',
                    name:'',
                    company:'',
                    inOrOut:'',
                    counterparty:'',
                    dateOfDoc:'',
                    typeOfDoc:'',
                    numberOnDoc:'',
                    description:'',
                    status:'',
                    created_at:'',
                    shortDesc:'',
                },
                document_id:'',
                pagination: {},
                edit_id:''
            };
        },
        created() {
            this.fetchDocuments();
    },
    methods:{
    fetchDocuments(page_url) {
      let vm = this;
      page_url = page_url || '/api/Doc';
      fetch(page_url)
        .then(res => res.json())
        .then(res => {
          this.documents = res.data;
          vm.makePagination(res.meta, res.links);
        })
        .catch(err => console.log(err));
        
    },
    makePagination(meta, links) {
      let pagination = {
        current_page: meta.current_page,
        last_page: meta.last_page,
        next_page_url: links.next,
        prev_page_url: links.prev
      };
      this.pagination = pagination;
    },

    downloadFile(id, name){
      if (confirm(`Pobrać dokument ${name} ?` )){
      axios({
  url: `/api/Doc/${id}`,
  method: 'GET',
  responseType: 'blob',})
  .then((response) => {
   const url = window.URL.createObjectURL(new Blob([response.data]));
   const link = document.createElement('a');
   link.href = url;
   link.setAttribute('download', `${name}`);
   document.body.appendChild(link);
   link.click();
});
    }
  },

  editDocument(document){
    this.edit_id = document.id;

  },
  cancelEdit(){
    this.edit_id=0;
    this.fetchDocuments();
  },

  addDocument(document) {
    this.edit_id = '';
    this.document.id = document.id;
    this.document.document_id = document.id;
    this.document.name = document.name;
    this.document.company = document.company;
    this.document.inOrOut = document.inOrOut;
    this.document.counterparty = document.counterparty;
    this.document.dateOfDoc = document.dateOfDoc;
    this.document.typeOfDoc = document.typeOfDoc;
    this.document.numberOnDoc = document.numberOnDoc;
    this.document.description = document.description;
    this.document.status = document.status;
        fetch(`/api/Doc`, {
          method: 'put',
          body: JSON.stringify(this.document),
          headers: {
            'content-type': 'application/json'
          }
        })
          .then(res => res.json())
          .then(data => {
            alert('Zapisano zmiany');
            this.fetchDocuments();
          })
          .catch(err => console.log(err));
          this.edit = false;
  },

  deleteDocument(id) {
      if (confirm('Usunąć dokument?')) {
        fetch(`api/Doc/${id}`, {
          method: 'delete'
        })
          .then(res => res.json())
          .then(data => {
            alert('Usunięto!');
            this.fetchDocuments();
          })
          .catch(err => console.log(err));
      }
  },
  displayDoc(id){
    window.open(`api/displayDoc/${id}`);
    }
  
  

  },
    computed: {
        filteredDocs() {

              return this.documents.filter((document)=> {
              document.id = document.id.toString();
              document.numberOnDoc = document.numberOnDoc.toString();

                return  (document.id.match(this.searchById))&&
                        (document.name.toUpperCase().match(this.searchByName.toUpperCase()))&&
                        (document.status.match(this.searchByStatus))&&
                        (document.counterparty.toUpperCase().match(this.searchByCounterparty.toUpperCase()))&&
                        (document.company.match(this.searchByCompany))&&
                        (document.inOrOut.match(this.searchByInOrOut))&&
                        (document.created_at.match(this.searchByCreatedAt))&&
                        (document.dateOfDoc.match(this.searchByDateOfDoc))&&
                        (document.typeOfDoc.match(this.searchByTypeOfDoc))&&
                        (document.numberOnDoc.match(this.searchByNumberOnDoc))&&
                        (document.description.toUpperCase().match(this.searchByDescription.toUpperCase()))
                        
            });
        },
    },
}
</script>