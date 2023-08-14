<template>
    

        
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Frequência do uso do nome no Brasil</h3>
                </div>
                <div class="card-body">
                    
                    <!-- TOKEN CSRF -->
                    <input type="hidden" name="token" :value="csrf_token">
                    <div class="form-group">
                        <label for="pesquisaValor">Pesquise o nome:</label>
                        <input type="text" class="form-control" id="pesquisaValor" placeholder="Digite o nome" maxlength="55" v-model="pesquisaValor" >
                        
                    </div>
                    <div class="form-check col-5 mt-3">
                        <input type="checkbox" class="form-check-input" name="decada" id="decada" v-model="decadaCheck">
                        <label class="form-check-label" for="decada">Década mais frequente</label>
                    </div>
                    <button class="btn btn-primary btn-block m-3" @click="pesquisa()">Pesquisar</button>
                    <button class="btn btn-primary btn-block m-3" @click="top10()">Top 10 nomes mais usados</button>
                    
                    <!-- TABELA -->
                    <table class="table mt-4" v-if="renderizarTable == true" >
                        <thead>
                            <tr>
                                <th scope="col" v-if="renderizarTop">Rank</th>
                                <th scope="col" v-if="renderizarTop">Nome</th>
                                <th scope="col" v-if="renderizarDecada || renderizarPesquisa">Década</th>

                                <th scope="col" v-if="renderizarTop || renderizarPesquisa || renderizarDecada">Frequência de uso</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <tr v-for="valor, chaveValor in resultado" :key="chaveValor">
                                <th scope="row" v-if="renderizarTop">{{ valor.rank }}</th>
                                <td v-if="renderizarTop">{{valor.nome}}</td>
                                <td v-if="renderizarTop">{{ valor.freq ? valor.freq.toLocaleString() : '' }}</td>

                                <td v-if="renderizarPesquisa">{{ valor.periodo ? valor.periodo.replace(/[\[\]]/g, "") : '' }}</td>
                                <td v-if="renderizarPesquisa">{{ valor.frequencia ? valor.frequencia.toLocaleString() : '' }}</td>

                            </tr>
                        </tbody>
                        
                        
                    </table>
                    
                    <div class="mt-3" v-if="renderizarDecada">               
                        <ul style="list-style: none">
                            <li style="font-size: 18px"> <span class="fw-bold"> Década: </span> {{decada}} </li>
                            <li style="font-size: 18px"> <span class="fw-bold"> Frequência: </span> {{freq}} </li>
                        </ul>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
        
</template>

<script>
    export default {
        props: ['csrf_token'],
        data() {
            return {
                url: 'http://localhost:8000/api/',
                urlNome: 'http://localhost:8000/api/nome/',
                urlDecada: 'http://localhost:8000/api/decada-mais-frequente/',
                pesquisaValor: '',
                decadaCheck: false,
                renderizarTable: false,
                renderizarDecada: false,
                renderizarPesquisa: false,            
                renderizarTop: false,
                resultado: null,
                decada: '',
                freq: '',
                erro: '',
            }
        }, 
        
        methods: {
            pesquisa() {
                
                if (this.decadaCheck == true) { //caso a checkbox esteja marcada
                    this.renderizarPesquisa = false
                    this.renderizarTable = false
                    this.renderizarDecada = true
                    let url = this.urlDecada + this.pesquisaValor
                    
                    axios.get(url)
                        .then(response => {
                            this.decada = response.data.decada.replace(/[\[\]]/g, "");
                            this.freq = response.data.freq.toLocaleString() + " vezes "
                            console.log(response.data)
                        })
                        .catch(errors => {
                            this.erro = errors.response.status
                            console.log(errors.response.status)
                        })

                    
                } else { //caso a checkbox esteja desmarcada
                    this.renderizarTop = false
                    this.renderizarDecada = false
                    this.renderizarTable = true //renderiza a table
                    this.renderizarPesquisa = true //renderiza colunas específicas caso a chekbox esteja desmarcada
                    let url = this.urlNome + this.pesquisaValor //MONTA A URL PARA A REQUEST

                    axios.get(url)
                        .then(response => { //CASO OCORRA TUDO BEM, MOSTRA A RESPOSTA DA REQUISIÇÃO NO CONSOLE
                            this.resultado = response.data[0].res
                            console.log(this.resultado)
                        })
                        .catch(errors => { //MOSTRA OS ERROS NA REQUISIÇÃO
                            this.erro = errors.response.status
                            console.log(errors.response.status)
                        })
                }

            },

            top10() {
                this.renderizarDecada = false
                this.renderizarTable = true //renderiza a table
                this.renderizarTop = true

                axios.get(this.url)
                    .then(response => {
                        this.resultado = response.data
                        console.log(this.resultado)
                    })
                    .catch(errors => {
                        console.log(errors.response)
                    })
            }
        }

        
    }
</script>
