<x-admin-layout title="Edit Case Study">
    @include('admin.case-studies._form', [
        'caseStudy' => $caseStudy,
        'action' => route('admin.case-studies.update', $caseStudy),
        'method' => 'PUT',
        'submitLabel' => 'Save Changes',
    ])
</x-admin-layout>
