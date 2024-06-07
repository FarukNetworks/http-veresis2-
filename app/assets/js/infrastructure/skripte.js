$(document).ready(function () {
	
	console.log('ready');

	// toastr setting to be very fast

	toastr.options = {
		"closeButton": false,
		"debug": false,
		"newestOnTop": true,
		"progressBar": true,
		"positionClass": "toast-top-right",
		"preventDuplicates": true,
		"onclick": null,
		"showDuration": "300",
		"hideDuration": "100",
		"timeOut": "50"
	};



	// 	<form action="#" id="k_configurator" class="k_configurator_class">
	// 	<div>
	// 		<div class="k-user-inputs-holder">

	// 			<div class="k-input-field">
	// 				<label for="prirezRate">Občina:</label>
	// 				<select id="k_obcine" class="k_obcine" name="k_obcine">
	// 					<option value="grosuplje">Grosuplje</option>
	// 					<option value="ljubljana">Ljubljana</option>
	// 				</select>
	// 			</div>

	// 			{# Tip tablice #}

	// 			{# Avto
	// 			Avto Ožane
	// 			Moped
	// 			R4
	// 			R4 Ožane
	// 			Motor/Traktor #}

	// 			<div class="k-input-field">
	// 				<label for="prirezRate">Tip tablice:</label>
	// 				<select id="k_tip_tablice" class="k_tip_tablice" name="k_obcine">
	// 					<option value="tip_avto">Avto</option>
	// 					<option value="tip_avto_o">Avto ožan</option>
	// 					<option value="tip_moped">Moped</option>
	// 					<option value="tip_r4">R4</option>
	// 					<option value="tip_r4_o">R4 ožan</option>
	// 					<option value="tip_traktor">Motor/Traktor</option>
	// 				</select>
	// 			</div>


	// 			<div class="k-input-field">
	// 				<label>Ime na tablici:</label>
	// 				<input required type="text" id="k_input_text" class="k-input-text" placeholder="Vpišite tukaj ..." />
	// 			</div>


	// 		</div>

	// 		<div class="k-user-submit-holder">
	// 			<label class="u-blank-label"></label>
	// 			<input type="submit" class="cg_calculate" value="Prikaži"></input>
	// 		</div>
	// 	</div>
	// </form>

	// Array for obcine

	// const k_obcine = [
	// 	{ "value": "ajdovscina", "name": "ajdovščina", "img": "ajdovscina.jpg" },
	// 	{ "value": "akvonij", "name": "akvonij", "img": "akvonij.jpg" },
	// 	{ "value": "apace", "name": "apače", "img": "apace.jpg" },
	// 	{ "value": "beltinci", "name": "beltinci", "img": "beltinci.jpg" },
	// 	{ "value": "benedikt", "name": "benedikt", "img": "benedikt.jpg" },
	// 	{ "value": "bistrica_ob_sotli", "name": "bistrica ob sotli", "img": "bistrica_ob_sotli.jpg" },
	// 	{ "value": "bistrica_pri_trzicu", "name": "bistrica pri tržiču", "img": "bistrica_pri_trzicu.jpg" },
	// 	{ "value": "bled", "name": "bled", "img": "bled.jpg" },
	// 	{ "value": "bloke", "name": "bloke", "img": "bloke.jpg" },
	// 	{ "value": "bohinj", "name": "bohinj", "img": "bohinj.jpg" },
	// 	{ "value": "borovnica", "name": "borovnica", "img": "borovnica.jpg" },
	// 	{ "value": "bovec", "name": "bovec", "img": "bovec.jpg" },
	// 	{ "value": "brda", "name": "brda", "img": "brda.jpg" },
	// 	{ "value": "brezovica", "name": "brezovica", "img": "brezovica.jpg" },
	// 	{ "value": "brezice", "name": "brežice", "img": "brezice.jpg" },
	// 	{ "value": "cankova", "name": "cankova", "img": "cankova.jpg" },
	// 	{ "value": "cerklje_na_gorenjskem", "name": "cerklje na gorenjskem", "img": "cerklje_na_gorenjskem.jpg" },
	// 	{ "value": "cerkno", "name": "cerkno", "img": "cerkno.jpg" },
	// 	{ "value": "cerkovci", "name": "cerkovci", "img": "cerkovci.jpg" },
	// 	{ "value": "cerkvenjak", "name": "cerkvenjak", "img": "cerkvenjak.jpg" },
	// 	{ "value": "cirovci", "name": "cirovci", "img": "cirovci.jpg" },
	// 	{ "value": "cirkulane", "name": "cirkulane", "img": "cirkulane.jpg" },
	// 	{ "value": "crensovci", "name": "črenšovci", "img": "crensovci.jpg" },
	// 	{ "value": "crna_na_koroskem", "name": "črna na koroškem", "img": "crna_na_koroskem.jpg" },
	// 	{ "value": "crnomelj", "name": "črnomelj", "img": "crnomelj.jpg" },
	// 	{ "value": "destrnik", "name": "destrnik", "img": "destrnik.jpg" },
	// 	{ "value": "divaca", "name": "divača", "img": "divaca.jpg" },
	// 	{ "value": "dobje", "name": "dobje", "img": "dobje.jpg" },
	// 	{ "value": "dobrepolje", "name": "dobrepolje", "img": "dobrepolje.jpg" },
	// 	{ "value": "dobrna", "name": "dobrna", "img": "dobrna.jpg" },
	// 	{ "value": "dobrova_polhov_gradec", "name": "dobrova - polhov gradec", "img": "dobrova_polhov_gradec.jpg" },
	// 	{ "value": "dobrovnik", "name": "dobrovnik", "img": "dobrovnik.jpg" },
	// 	{ "value": "dol_pri_ljubljani", "name": "dol pri ljubljani", "img": "dol_pri_ljubljani.jpg" },
	// 	{ "value": "dolenjske_toplice", "name": "dolenjske toplice", "img": "dolenjske_toplice.jpg" },
	// 	{ "value": "domzale", "name": "domžale", "img": "domzale.jpg" },
	// 	{ "value": "dornava", "name": "dornava", "img": "dornava.jpg" },
	// 	{ "value": "dravograd", "name": "dravograd", "img": "dravograd.jpg" },
	// 	{ "value": "duplek", "name": "duplek", "img": "duplek.jpg" },
	// 	{ "value": "gorenja_vas_poljane", "name": "gorenja vas - poljane", "img": "gorenja_vas_poljane.jpg" },
	// 	{ "value": "gorisnica", "name": "gorišnica", "img": "gorisnica.jpg" },
	// 	{ "value": "gorje", "name": "gorje", "img": "gorje.jpg" },
	// 	{ "value": "gornja_radgona", "name": "gornja radgona", "img": "gornja_radgona.jpg" },
	// 	{ "value": "gornji_grad", "name": "gornji grad", "img": "gornji_grad.jpg" },
	// 	{ "value": "gornji_petrovci", "name": "gornji petrovci", "img": "gornji_petrovci.jpg" },
	// 	{ "value": "grad", "name": "grad", "img": "grad.jpg" },
	// 	{ "value": "grosuplje", "name": "grosuplje", "img": "grosuplje.jpg" },
	// 	{ "value": "hajdina", "name": "hajdina", "img": "hajdina.jpg" },
	// 	{ "value": "hoce_slivnica", "name": "hoče - slivnica", "img": "hoce_slivnica.jpg" },
	// 	{ "value": "horjul", "name": "horjul", "img": "horjul.jpg" },
	// 	{ "value": "hrastnik", "name": "hrastnik", "img": "hrastnik.jpg" },
	// 	{ "value": "hrpelje_kozina", "name": "hrpelje - kozina", "img": "hrpelje_kozina.jpg" },
	// 	{ "value": "idrija", "name": "idrija", "img": "idrija.jpg" },
	// 	{ "value": "ig", "name": "ig", "img": "ig.jpg" },
	// 	{ "value": "ilirska_bistrica", "name": "ilirska bistrica", "img": "ilirska_bistrica.jpg" },
	// 	{ "value": "ivanjkovci", "name": "ivanjkovci", "img": "ivanjkovci.jpg" },
	// 	{ "value": "izola", "name": "izola", "img": "izola.jpg" },
	// 	{ "value": "jenec", "name": "jeneč", "img": "jenec.jpg" },
	// 	{ "value": "jesenice", "name": "jesenice", "img": "jesenice.jpg" },
	// 	{ "value": "jursinci", "name": "juršinci", "img": "jursinci.jpg" },
	// 	{ "value": "kamnik", "name": "kamnik", "img": "kamnik.jpg" },
	// 	{ "value": "kanal_ob_soci", "name": "kanal ob soči", "img": "kanal_ob_soci.jpg" },
	// 	{ "value": "kidricevo", "name": "kidričevo", "img": "kidricevo.jpg" },
	// 	{ "value": "kobarid", "name": "kobarid", "img": "kobarid.jpg" },
	// 	{ "value": "kocevje", "name": "kočevje", "img": "kocevje.jpg" },
	// 	{ "value": "kobilje", "name": "kobilje", "img": "kobilje.jpg" },
	// 	{ "value": "komenda", "name": "komenda", "img": "komenda.jpg" },
	// 	{ "value": "koper", "name": "koper", "img": "koper.jpg" },
	// 	{ "value": "kostel", "name": "kostel", "img": "kostel.jpg" },
	// 	{ "value": "kozje", "name": "kozje", "img": "kozje.jpg" },
	// 	{ "value": "kranjska_gora", "name": "kranjska gora", "img": "kranjska_gora.jpg" },
	// 	{ "value": "krsko", "name": "krško", "img": "krsko.jpg" },
	// 	{ "value": "kungota", "name": "kungota", "img": "kungota.jpg" },
	// 	{ "value": "kuzma", "name": "kuzma", "img": "kuzma.jpg" },
	// 	{ "value": "lasko", "name": "laško", "img": "lasko.jpg" },
	// 	{ "value": "lenart", "name": "lenart", "img": "lenart.jpg" },
	// 	{ "value": "lendava", "name": "lendava", "img": "lendava.jpg" },
	// 	{ "value": "litija", "name": "litija", "img": "litija.jpg" },
	// 	{ "value": "ljubljana", "name": "ljubljana", "img": "ljubljana.jpg" },
	// 	{ "value": "ljubno", "name": "ljubno", "img": "ljubno.jpg" },
	// 	{ "value": "ljutomer", "name": "ljutomer", "img": "ljutomer.jpg" },
	// 	{ "value": "loge_sostro", "name": "loge-sostro", "img": "loge_sostro.jpg" },
	// 	{ "value": "logatec", "name": "logatec", "img": "logatec.jpg" },
	// 	{ "value": "lovrenc_na_pohorju", "name": "lovrenc na pohorju", "img": "lovrenc_na_pohorju.jpg" },
	// 	{ "value": "luce", "name": "luče", "img": "luce.jpg" },
	// 	{ "value": "lukovica", "name": "lukovica", "img": "lukovica.jpg" },
	// 	{ "value": "majsperk", "name": "majšperk", "img": "majsperk.jpg" },
	// 	{ "value": "makole", "name": "makole", "img": "makole.jpg" },
	// 	{ "value": "maribor", "name": "maribor", "img": "maribor.jpg" },
	// 	{ "value": "markovci", "name": "markovci", "img": "markovci.jpg" },
	// 	{ "value": "medvode", "name": "medvode", "img": "medvode.jpg" },
	// 	{ "value": "menges", "name": "mengeš", "img": "menges.jpg" },
	// 	{ "value": "metlika", "name": "metlika", "img": "metlika.jpg" },
	// 	{ "value": "mezica", "name": "mežica", "img": "mezica.jpg" },
	// 	{ "value": "miklavz_na_dravskem_polju", "name": "miklavž na dravskem polju", "img": "miklavz_na_dravskem_polju.jpg" },
	// 	{ "value": "miren_kostanjevica", "name": "miren-kostanjevica", "img": "miren_kostanjevica.jpg" },
	// 	{ "value": "mirna", "name": "mirna", "img": "mirna.jpg" },
	// 	{ "value": "mirna_pec", "name": "mirna peč", "img": "mirna_pec.jpg" },
	// 	{ "value": "mislinja", "name": "mislinja", "img": "mislinja.jpg" },
	// 	{ "value": "mokronog_trebelno", "name": "mokronog-trebelno", "img": "mokronog_trebelno.jpg" },
	// 	{ "value": "moravce", "name": "moravče", "img": "moravce.jpg" },
	// 	{ "value": "moravske_toplice", "name": "moravske toplice", "img": "moravske_toplice.jpg" },
	// 	{ "value": "mozirska_mislinjska_dolina", "name": "mozirska mislinjska dolina", "img": "mozirska_mislinjska_dolina.jpg" },
	// 	{ "value": "mozirje", "name": "mozirje", "img": "mozirje.jpg" },
	// 	{ "value": "murska_sobota", "name": "murska sobota", "img": "murska_sobota.jpg" },
	// 	{ "value": "muta", "name": "muta", "img": "muta.jpg" },
	// 	{ "value": "naklo", "name": "naklo", "img": "naklo.jpg" },
	// 	{ "value": "nazarje", "name": "nazarje", "img": "nazarje.jpg" },
	// 	{ "value": "novigrad", "name": "novigrad", "img": "novigrad.jpg" },
	// 	{ "value": "novo_mesto", "name": "novo mesto", "img": "novo_mesto.jpg" },
	// 	{ "value": "odra", "name": "odra", "img": "odra.jpg" },
	// 	{ "value": "opcine", "name": "opčine", "img": "opcine.jpg" },
	// 	{ "value": "ormoz", "name": "ormož", "img": "ormoz.jpg" },
	// 	{ "value": "osilnica", "name": "osilnica", "img": "osilnica.jpg" },
	// 	{ "value": "pesnica", "name": "pesnica", "img": "pesnica.jpg" },
	// 	{ "value": "petrovci", "name": "petrovci", "img": "petrovci.jpg" },
	// 	{ "value": "piran", "name": "piran", "img": "piran.jpg" },
	// 	{ "value": "pivka", "name": "pivka", "img": "pivka.jpg" },
	// 	{ "value": "podcetrtek", "name": "podčetrtek", "img": "podcetrtek.jpg" },
	// 	{ "value": "podeg", "name": "podeg", "img": "podeg.jpg" },
	// 	{ "value": "podlehnik", "name": "podlehnik", "img": "podlehnik.jpg" },
	// 	{ "value": "podvelka", "name": "podvelka", "img": "podvelka.jpg" },
	// 	{ "value": "poljane_nad_skofjo_loko", "name": "poljane nad škofjo loko", "img": "poljane_nad_skofjo_loko.jpg" },
	// 	{ "value": "polzela", "name": "polzela", "img": "polzela.jpg" },
	// 	{ "value": "postojna", "name": "postojna", "img": "postojna.jpg" },
	// 	{ "value": "prebold", "name": "prebold", "img": "prebold.jpg" },
	// 	{ "value": "preddvor", "name": "preddvor", "img": "preddvor.jpg" },
	// 	{ "value": "prevalje", "name": "prevalje", "img": "prevalje.jpg" },
	// 	{ "value": "ptuj", "name": "ptuj", "img": "ptuj.jpg" },
	// 	{ "value": "puconci", "name": "puconci", "img": "puconci.jpg" },
	// 	{ "value": "race_fram", "name": "rače - fram", "img": "race_fram.jpg" },
	// 	{ "value": "radenci", "name": "radenci", "img": "radenci.jpg" },
	// 	{ "value": "radlje_ob_dravi", "name": "radlje ob dravi", "img": "radlje_ob_dravi.jpg" },
	// 	{ "value": "radovljica", "name": "radovljica", "img": "radovljica.jpg" },
	// 	{ "value": "ravne_na_koroskem", "name": "ravne na koroškem", "img": "ravne_na_koroskem.jpg" },
	// 	{ "value": "razkrizje", "name": "razkrižje", "img": "razkrizje.jpg" },
	// 	{ "value": "recica_ob_savinji", "name": "rečica ob savinji", "img": "recica_ob_savinji.jpg" },
	// 	{ "value": "rence_vogrsko", "name": "renče - vogrsko", "img": "rence_vogrsko.jpg" },
	// 	{ "value": "resko", "name": "resko", "img": "resko.jpg" },
	// 	{ "value": "ribnica", "name": "ribnica", "img": "ribnica.jpg" },
	// 	{ "value": "ribnica_na_pohorju", "name": "ribnica na pohorju", "img": "ribnica_na_pohorju.jpg" },
	// 	{ "value": "rogasovci", "name": "rogašovci", "img": "rogasovci.jpg" },
	// 	{ "value": "rogatec", "name": "rogatec", "img": "rogatec.jpg" },
	// 	{ "value": "ruse", "name": "ruše", "img": "ruse.jpg" },
	// 	{ "value": "selnica_ob_dravi", "name": "selnica ob dravi", "img": "selnica_ob_dravi.jpg" },
	// 	{ "value": "semic", "name": "semič", "img": "semic.jpg" },
	// 	{ "value": "sevnica", "name": "sevnica", "img": "sevnica.jpg" },
	// 	{ "value": "sezana", "name": "sežana", "img": "sezana.jpg" },
	// 	{ "value": "slovenj_gradec", "name": "slovenj gradec", "img": "slovenj_gradec.jpg" },
	// 	{ "value": "slovenska_bistrica", "name": "slovenska bistrica", "img": "slovenska_bistrica.jpg" },
	// 	{ "value": "slovenske_konjice", "name": "slovenske konjice", "img": "slovenske_konjice.jpg" },
	// 	{ "value": "salovci", "name": "šalovci", "img": "salovci.jpg" },
	// 	{ "value": "sempeter_vrtojba", "name": "šempeter - vrtojba", "img": "sempeter_vrtojba.jpg" },
	// 	{ "value": "sentilj", "name": "šentilj", "img": "sentilj.jpg" },
	// 	{ "value": "sentjernej", "name": "šentjernej", "img": "sentjernej.jpg" },
	// 	{ "value": "sentjur", "name": "šentjur", "img": "sentjur.jpg" },
	// 	{ "value": "stepanjsko_naselje", "name": "štepanjsko naselje", "img": "stepanjsko_naselje.jpg" },
	// 	{ "value": "sentvid_pri_sticni", "name": "šentvid pri stični", "img": "sentvid_pri_sticni.jpg" },
	// 	{ "value": "seskov", "name": "šeškov", "img": "seskov.jpg" },
	// 	{ "value": "seskov_dom", "name": "šeškov dom", "img": "seskov_dom.jpg" },
	// 	{ "value": "ska", "name": "ška", "img": "ska.jpg" },
	// 	{ "value": "skocjan", "name": "škocjan", "img": "skocjan.jpg" },
	// 	{ "value": "skofja_loka", "name": "škofja loka", "img": "skofja_loka.jpg" },
	// 	{ "value": "skofljica", "name": "škofljica", "img": "skofljica.jpg" },
	// 	{ "value": "store", "name": "štore", "img": "store.jpg" },
	// 	{ "value": "straza", "name": "straža", "img": "straza.jpg" },
	// 	{ "value": "sveti_andraz_v_sloven", "name": "sveti andraž v slovenskih goricah", "img": "sveti_andraz_v_sloven.jpg" },
	// 	{ "value": "sveti_jurij_ob_scavnici", "name": "sveti jurij ob ščavnici", "img": "sveti_jurij_ob_scavnici.jpg" },
	// 	{ "value": "sveti_jurij_v_slovenj", "name": "sveti jurij v slovenj", "img": "sveti_jurij_v_slovenj.jpg" },
	// 	{ "value": "sveti_peter", "name": "sveti peter", "img": "sveti_peter.jpg" },
	// 	{ "value": "sveti_trojica_v_slovenj", "name": "sveti trojica v slovenj", "img": "sveti_trojica_v_slovenj.jpg" },
	// 	{ "value": "sveti_urh", "name": "sveti urh", "img": "sveti_urh.jpg" },
	// 	{ "value": "svetvi_urh", "name": "svetvi urh", "img": "svetvi_urh.jpg" },
	// 	{ "value": "svabice", "name": "švabice", "img": "svabice.jpg" },
	// 	{ "value": "svetka", "name": "svetka", "img": "svetka.jpg" },
	// 	{ "value": "taborska", "name": "taborska", "img": "taborska.jpg" },
	// 	{ "value": "tabor", "name": "tabor", "img": "tabor.jpg" },
	// 	{ "value": "tisina", "name": "tišina", "img": "tisina.jpg" },
	// 	{ "value": "tolmin", "name": "tolmin", "img": "tolmin.jpg" },
	// 	{ "value": "trbovlje", "name": "trbovlje", "img": "trbovlje.jpg" },
	// 	{ "value": "trebnje", "name": "trebnje", "img": "trebnje.jpg" },
	// 	{ "value": "trnovska_vas", "name": "trnovska vas", "img": "trnovska_vas.jpg" },
	// 	{ "value": "trzin", "name": "trzin", "img": "trzin.jpg" },
	// 	{ "value": "turnisce", "name": "turnišče", "img": "turnisce.jpg" },
	// 	{ "value": "velenje", "name": "velenje", "img": "velenje.jpg" },
	// 	{ "value": "velike_lasce", "name": "velike lašče", "img": "velike_lasce.jpg" },
	// 	{ "value": "verzej", "name": "veržej", "img": "verzej.jpg" },
	// 	{ "value": "vitanje", "name": "vitanje", "img": "vitanje.jpg" },
	// 	{ "value": "vodice", "name": "vodice", "img": "vodice.jpg" },
	// 	{ "value": "vojnik", "name": "vojnik", "img": "vojnik.jpg" },
	// 	{ "value": "vransko", "name": "vransko", "img": "vransko.jpg" },
	// 	{ "value": "vrhnika", "name": "vrhnika", "img": "vrhnika.jpg" },
	// 	{ "value": "vuzenica", "name": "vuzenica", "img": "vuzenica.jpg" },
	// 	{ "value": "zagorje_ob_savi", "name": "zagorje ob savi", "img": "zagorje_ob_savi.jpg" },
	// 	{ "value": "zavrc", "name": "zavrč", "img": "zavrc.jpg" },
	// 	{ "value": "zelezniki", "name": "železniki", "img": "zelezniki.jpg" },
	// 	{ "value": "zetale", "name": "žetale", "img": "zetale.jpg" },
	// 	{ "value": "ziri", "name": "žiri", "img": "ziri.jpg" },
	// 	{ "value": "zirovnica", "name": "žirovnica", "img": "zirovnica.jpg" },
	// 	{ "value": "zuzemberk", "name": "žužemberk", "img": "zuzemberk.jpg" },
	// 	{ "value": "zrece", "name": "zreče", "img": "zrece.jpg" }
	// ];

	const k_obcine_list = [
		{ "value": "ajdovscina", "name": "ajdovščina", "img": "ajdovscina.png", "abbr": "GO" },
		{ "value": "brezice", "name": "brežice", "img": "brezice.png", "abbr": "KK" },
		{ "value": "crna_na_koroskem", "name": "črna na koroškem", "img": "crna_na_koroskem.png", "abbr": "SG" },
		{ "value": "crnomelj", "name": "črnomelj", "img": "crnomelj.png", "abbr": "NM" },
		{ "value": "dravograd", "name": "dravograd", "img": "dravograd.png", "abbr": "SG" },
		{ "value": "domzale", "name": "domžale", "img": "domzale.png", "abbr": "LJ" },
		{ "value": "gornja_radgona", "name": "gornja radgona", "img": "gornja_radgona.png", "abbr": "MS" },
		{ "value": "grosuplje", "name": "grosuplje", "img": "grosuplje.png", "abbr": "LJ" },
		{ "value": "idrija", "name": "idrija", "img": "idrija.png", "abbr": "GO" },
		{ "value": "jesenice", "name": "jesenice", "img": "jesenice.png", "abbr": "KR" },
		{ "value": "kamnik", "name": "kamnik", "img": "kamnik.png", "abbr": "LJ" },
		{ "value": "koper", "name": "koper", "img": "koper.png", "abbr": "KP" },
		{ "value": "kranj", "name": "kranj", "img": "kranj.png", "abbr": "KR" },
		{ "value": "krsko", "name": "krško", "img": "krsko.png", "abbr": "KK" },
		{ "value": "lasko", "name": "laško", "img": "lasko.png", "abbr": "CE" },
		{ "value": "ljubljana", "name": "ljubljana", "img": "ljubljana.png", "abbr": "LJ" },
		{ "value": "ljutomer", "name": "ljutomer", "img": "ljutomer.png", "abbr": "MS" },
		{ "value": "maribor", "name": "maribor", "img": "maribor.png", "abbr": "MB" },
		{ "value": "metlika", "name": "metlika", "img": "metlika.png", "abbr": "NM" },
		{ "value": "mirska_sobota", "name": "murska sobota", "img": "murska_sobota.png", "abbr": "MS" },
		{ "value": "nova_gorica", "name": "nova gorica", "img": "nova_gorica.png", "abbr": "GO" },
		{ "value": "novo_mesto", "name": "novo mesto", "img": "novo_mesto.png", "abbr": "NM" },
		{ "value": "ormoz", "name": "ormož", "img": "ormoz.png", "abbr": "MB" },
		{ "value": "pesnica", "name": "pesnica", "img": "pesnica.png", "abbr": "MB" },
		{ "value": "piran", "name": "piran", "img": "piran.png", "abbr": "KP" },
		{ "value": "postojna", "name": "postojna", "img": "postojna.png", "abbr": "PO" },
		{ "value": "ptuj", "name": "ptuj", "img": "ptuj.png", "abbr": "MB" },
		{ "value": "radlje_ob_dravi", "name": "radlje ob dravi", "img": "radlje_ob_dravi.png", "abbr": "SG" },
		{ "value": "radovljica", "name": "radovljica", "img": "radovljica.png", "abbr": "KR" },
		{ "value": "ravne_na_koroskem", "name": "ravne na koroškem", "img": "ravne_na_koroskem.png", "abbr": "SG" },
		{ "value": "sevnica", "name": "sevnica", "img": "sevnica.png", "abbr": "KK" },
		{ "value": "sezana", "name": "sežana", "img": "sezana.png", "abbr": "KP" },
		{ "value": "slovenj_gradec", "name": "slovenj gradec", "img": "slovenj_gradec.png", "abbr": "SG" },
		{ "value": "slovenska_bistrica", "name": "slovenska bistrica", "img": "slovenska_bistrica.png", "abbr": "MB" },
		{ "value": "sentjur", "name": "šentjur", "img": "sentjur.png", "abbr": "CE" },
		{ "value": "skofja_loka", "name": "škofja loka", "img": "skofja_loka.png", "abbr": "KR" },
		{ "value": "velenje", "name": "velenje", "img": "velenje.png", "abbr": "CE" },
		{ "value": "zagorje_ob_savi", "name": "zagorje ob savi", "img": "zagorje_ob_savi.png", "abbr": "LJ" },
		{ "value": "zelezniki", "name": "železniki", "img": "zelezniki.png", "abbr": "zl" }
	];

	const k_tip_tablice = [
		{
			"value": "tip_avto",
			"img": "avto.jpg",
			"class": "avto-font",
			"holder": "tablica-avto"
		},
		{
			"value": "tip_avto_o",
			"img": "avto.jpg",
			"class": "avto-oz",
			"holder": "tablica-avto"
		},
		{
			"value": "tip_moped",
			"img": "Scooter-tablica.jpg",
			"class": "moped-font",
			"holder": "tablica-moped"

		},
		{
			"value": "tip_r4",
			"img": "r4.jpg",
			"class": "r4-font",
			"holder": "tablica-r4"
		},
		{
			"value": "tip_r4_o",
			"img": "r4_o.jpg",
			"class": "r4-oz",
			"holder": "tablica-r4"
		},
		{
			"value": "tip_traktor",
			"img": "traktor.jpg",
			"class": "traktor-font",
			"holder": "tablica-traktor"
		}
	];

	// Add options to select id k_obcine from const k_obcine_list
	$.each(k_obcine_list, function (index, value) {
		$('#k_obcine').append('<option abbr="'+ value.abbr +'" value="' + value.value + '">' + value.name + ' (' + value.abbr + ')' + '</option>');
	});

	function validateInput(input) {
		// Check if input contains at least one letter
		if (!/[a-zA-Z]/.test(input)) {
			return false;
		}
	
		// Check if input contains at most one dash and not at the beginning or the end
		if ((input.indexOf('-') !== input.lastIndexOf('-')) || input.startsWith('-') || input.endsWith('-')) {
			return false;
		}
	
		return true;
	}

	// on change of k_input and if k_vanity to true put a minus after 3 character of k_input

	$('#k_input_text').on('input change', function () {
		var input = $(this).val();
	
		// if vanity is false put a minus after 3 character of k_input
		// if vanity is true minus is not needed
		// if (!$('#k_vanity').is(':checked')) {
		// 	if (input.length === 3 || input.length === 4) {
		// 		input += '-';
		// 	}
		// }
	
		$(this).val(input);
	
		if (!validateInput(input)) {
			toastr.error('Vnesite veljavno registrsko oznako!');
		} else {
			toastr.success('Registrska oznaka je veljavna!');
		}
	});

	// switch placeholder based on k_tip_tablice

	// tip_avto, tip_avto_o - placeholder: AB-1234, AB-1234 
	// tip_traktor - AB-12
	

	// tip_moped, tip_r4, tip_r4_o, tip_traktor

	$('.k_tip_tablice').on('change', function () {

		var k_tip_value = $('#k_tip_tablice').val();

		if (k_tip_value === 'tip_avto' || k_tip_value === 'tip_avto_o') {
			$('.k-input-text').val('ABC-122');
		} else if (k_tip_value === 'tip_traktor') {
			$('.k-input-text').val('AB-12');
		} else if (k_tip_value === 'tip_moped') {
			$('.k-input-text').val('AB-12');
		

		} else {
			$('.k-input-text').val('ABC-122');
		}

		
	});


	// if vanity is check or uncheck always clear input k_input_text

	$('#k_vanity').on('change', function () {
		$('#k_input_text').val('');
	});




	$('#k_configurator').on('submit', function (e) {
		// Prevent default form submission in both cases
		e.preventDefault();

		function getValueFromArray(array, value) {
			return array.find(function (element) {
				return element.value === value;
			});
		}
	
		var isValid = true;

		$('input[type="text"]', this).each(function () {
			if ($(this).val() === '') {
				isValid = false;
				console.log('empty field');
				// make an error toastr
				toastr.error('Vnesite obvezna polja!');

				return false; // break the loop
			}
		});

		if (isValid) {

			// get values from form fields and store them in variables

			var k_obcine = $('#k_obcine').val();
			var k_tip_value = $('#k_tip_tablice').val();
			var k_ime_tablice = $('#k_input_text').val();
			

			var k_obcine_abbr = $('#k_obcine option:selected').text().split(' ')[1].slice(1, -1);
			var k_vanity = $('#k_vanity').val();
			var k_input = $('#k_input_text').val().toUpperCase();
			var k_tip_class = getValueFromArray(k_tip_tablice, k_tip_value).class;
			var k_holder_tablica = getValueFromArray(k_tip_tablice, k_tip_value).holder;

			// console log values
			console.log(k_obcine);
			console.log(k_tip_value);
			console.log(k_ime_tablice);
			console.log("k_holder_tablica: " + k_holder_tablica);

			// id "logger-result" i need to display every choosen variable
			var pillsHtml = `
			<div class="v-pills">
			  <span class="pill">Kraj: ${k_obcine}</span>
			  <span class="pill">Tip tablice: ${k_tip_value}</span>
			  <span class="pill">Ime: ${k_ime_tablice}</span>
			  <span class="pill">Okrajšava: ${k_obcine_abbr}</span>
			  <span class="pill">Vanity: ${k_vanity}</span>
			 
			  <span class="pill">Tip class: ${k_tip_class}</span>
			  <span class="pill">Tablica template: ${k_holder_tablica}</span>
			</div>
			`;
			
			$('#logger-result').html(pillsHtml);

			// change the name of the url in tab-grb class - images are located in 2 folder above and img/grbi and value
			$('.tab-grb img').attr('src', 'app/assets/img/grbi/' + k_obcine + '.png');

			// input abbr to the span in tab-okr class
			$('.tab-okr span').text(k_obcine_abbr);

			// input is put to tab-text-1
			$('.tab-text-1').text(k_input.toUpperCase());

			// remove default class and add class from k_tip_class
			$('.tab-wrapper').removeClass('avto-font avto-oz traktor-font r4-font r4-oz');
			$('.tab-wrapper').addClass(k_tip_class);

			// switch id based on k_holder_tablica

			$('.tablice-holder').attr('id', k_holder_tablica);

		}

	});

	/** cookies */
	if (!$(".cookies").hasClass("ne-pokazi")) {
		$(".cookies").fadeIn(400);
	}

	/* preloader */
	setTimeout(function(){
		$("#preloader-first").fadeOut(600);
	}, 1600);

	// setTimeout(function(){
	// 	$("#preloader").fadeOut(600);
	// }, 800);

	setTimeout(function(){
		$(".footer").slideDown();
	}, 800);

	/* search */

	$('.navbar-form').submit(function(e) {

		if($('#searchValue').val() == "") {
			e.preventDefault();
		}

	});

	$('#searchValue').keyup(function() {
		if($('#searchValue').val() !== "") {
			$('.searchButton').fadeIn(400);
		}
		if($('#searchValue').val() == "") {
			$('.searchButton').fadeOut(100);
		}
	});

	/* sidebar menu show hide */

	$('.sidebar-button').click(function() {
		$('.sidebar').toggle(1);
		$('.main').toggleClass("toggled");
		$('.footer').toggleClass("toggled");
		$('.sidebar-button').toggleClass("glyphicon-circle-arrow-right");
		$('.sidebar-button').toggleClass("glyphicon-circle-arrow-left");
	});

	$(window).resize(function(){
	// odstrani classe ob širini
		if ($(window).width() > 768) {
			$('.main').removeClass('toggled');
			$('.footer').removeClass('toggled');
			$('.sidebar').css('display','block');

			if ($('.main').hasClass('toggled-main')) {
				$('.main').removeClass('toggled-main');
			}
		}
		if ($(window).width() < 768) {
			$('.sidebar').css('display','none');
			$('.main').addClass('toggled-main');
			$('.sidebar-button').removeClass('glyphicon-circle-arrow-left');
			$('.sidebar-button').addClass('glyphicon-circle-arrow-right');
		}
	});

	$(window).load(function(){
	// odstrani classe ob širini
		if ($(window).width() > 768) {
			if ($('.main').hasClass('toggled-main')) {
				$('.main').removeClass('toggled-main');
			}
		}
		if ($(window).width() < 768) {
			$('.main').addClass('toggled-main');
		}
	});

	// validiraj password
	function validatePassword(password) {
		var pattern = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
		return pattern.test(password);
	}

	// validiraj email
    function isValidEmailAddress(email) {
        var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
        return pattern.test(email);
    }

	function preveriPassword() {
		var password1 = $('#password').val();
		var password2 = $('#password2').val();

		if (password1 !== password2) {
			$('.clientNapisErrorNoMatch').show();
			$('.clientNapisErrorNoMatch').text('Gesli se ne ujemata.');
		} else {
			$('.clientNapisErrorNoMatch').hide();
		}
	}

	/* validacija pozabljenega gesla -> emaila preko mail notifikacija */

	// email)

    // $("#email").change(function() {

    // if (!isValidEmailAddress($('#email').val())) {
    //         $('.clientNapisError').show();
    //         $('.clientNapisError').text('Pravilno vnesite elektronski naslov.');
    //     } else {
    //         $('.clientNapisError').hide();
    //     }
    // });

    $("#sendMailNotification").submit(function(e) {

		if (!isValidEmailAddress($('#email').val())) {
            $('.clientNapisError').show();
            $('.clientNapisError').text('Pred potrditvijo pravilno vnesite elektronski naslov.');
        	e.preventDefault();
        } else {
            $('.clientNapisError').hide();
        }

	});

	$("#changePasswordForm").submit(function(e) {

		var password1 = $('#password').val();
		var password2 = $('#password2').val();

		if (!validatePassword(password1)) {
			$('.clientNapisError').show();
			$('.clientNapisError').text('Geslo mora biti dolgo minimalno 6 znakov. Vsebovati mora: 1 veliko črko, 1 majhno črko in 1 številko.');
			e.preventDefault();
		} else {
			$('.clientNapisError').hide();
		}

		if (!validatePassword(password2)) {
			$('.clientNapisError').show();
			$('.clientNapisError').text('Geslo mora biti dolgo minimalno 6 znakov. Vsebovati mora: 1 veliko črko, 1 majhno črko in 1 številko.');
			e.preventDefault();
		} else {
			$('.clientNapisError').hide();
		}

		if (password1 !== password2) {
			$('.clientNapisErrorNoMatch').show();
			$('.clientNapisErrorNoMatch').text('Gesli se ne ujemata.');
			e.preventDefault();
		} else {
			$('.clientNapisErrorNoMatch').hide();
		}

	});

	// logout
	$(".logout").click(function (e) {
		bootbox.dialog({
			title: "Potrditev odjave",
			message: "Ali ste prepričani, da se želite odjaviti?",
			buttons: {
				cancel: {
					label: "&nbsp;Ne&nbsp;",
					className: "btn-default",
				},
				confirm: {
					label: "&nbsp;Da, odjavi me&nbsp;",
					className: "btn-primary",
					callback: function () {
						location.href= siteUrl + '/odjava';
					}
				},
			}
		});
	});

	// // confirm mail send
	// $(".poslji").click(function (e) {
	// 	bootbox.dialog({
	// 		title: "Potrditev pošiljanja",
	// 		message: "Število izbranih tablic: " + $('input:checkbox:checked').val('poslji-po-posti').length + ".<br> Vsaka tablica bo poslana posebej. Ste prepričani, da želite nadaljevati?",
	// 		buttons: {
	// 			cancel: {
	// 				label: "&nbsp;Ne&nbsp;",
	// 				className: "btn-default",
	// 			},
	// 			confirm: {
	// 				label: "&nbsp;Da&nbsp;",
	// 				className: "btn-primary",
	// 				callback: function () {
	// 					location.href= siteUrl + '/odjava';
	// 				}
	// 			},
	// 		}
	// 	});
	// });
});


$(document).ajaxComplete(function () {

	/* checkbox poslji - unicene tablice */
	$('.poslji-po-posti').click(function() {
		if ($('input:checkbox').is(':checked')) {
			$(".poslji").show();
		} else {
			$(".poslji").hide();
		}
	});


	
    /* next - prev */
    $('.prev').on('click', function() {
    	// $("#preloaderImage").show();
    	// alert("dela");
 //        $('.table-unicene').addClass('animated fadeOut');
 //        $('.registrationPicture').addClass('animated fadeOut');

 //        setTimeout(function(){
	// 	$('.table-unicene').removeClass('animated fadeOut');
 //        $('.registrationPicture').removeClass('animated fadeOut');
	// }, 800);
		$("#preloaderImage").show();
    });

    $('.next').on('click', function() {
    	// $("#preloaderImage").show();
    	// alert("dela");
 //        $('.table-unicene').addClass('animated fadeOut');
 //        $('.registrationPicture').addClass('animated fadeOut');

 //        setTimeout(function(){
	// 	$('.table-unicene').removeClass('animated fadeOut');
 //        $('.registrationPicture').removeClass('animated fadeOut');
	// }, 800);
		$("#preloaderImage").show();
    });
  

    $(".showPlate").on('click', function() {
    	$("#preloaderImage").show();
    });

	
});