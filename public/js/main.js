const myModal = document.getElementById('myModal');
const closeModalBtn = document.getElementById('closeModal');

closeModalBtn.addEventListener('click', () => {
	myModal.close();
});

// Close modal when clicking outside (for <dialog> element)
myModal.addEventListener('click', (event) => {
	if (event.target === myModal) {
		myModal.close();
	}
});

function openModal() {
	$('#dialogContainer').load('/?page=company', function() {
		$('#dialogContainer').dialog({
			modal: true, // Makes it a modal dialog
			autoOpen: true // Opens on page load
		});
	});
	myModal.showModal();
}
