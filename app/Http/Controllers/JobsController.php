<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    public function index()
    {
        return view('pages.jobs');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'gender'            => 'nullable|in:male,female,other',
            'email'             => 'required|email|max:200',
            'phone'             => 'required|string|max:20',
            'whatsapp'          => 'nullable|string|max:20',
            'city'              => 'nullable|string|max:100',
            'position'          => 'required|string|max:100',
            'experience_level'  => 'nullable|string|max:50',
            'education_level'   => 'nullable|string|max:50',
            'availability'      => 'nullable|string|max:50',
            'salary_expectation'=> 'nullable|string|max:50',
            'linkedin_url'      => 'nullable|url|max:300',
            'portfolio_url'     => 'nullable|url|max:300',
            'photo'             => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'cv'                => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'cover_letter'      => 'nullable|string|max:3000',
            'captcha_answer'    => 'required|integer',
            'captcha_sum'       => 'required|integer',
        ]);

        if ((int) $request->captcha_answer !== (int) $request->captcha_sum) {
            return back()->withInput()->withErrors(['captcha_answer' => 'Wrong answer. Please try again.']);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('job-applications/photos', 'public');
        }

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('job-applications/cvs', 'public');
        }

        JobApplication::create([
            'first_name'        => $data['first_name'],
            'last_name'         => $data['last_name'],
            'gender'            => $data['gender'] ?? null,
            'email'             => $data['email'],
            'phone'             => $data['phone'],
            'whatsapp'          => $data['whatsapp'] ?? null,
            'city'              => $data['city'] ?? null,
            'position'          => $data['position'],
            'experience_level'  => $data['experience_level'] ?? null,
            'education_level'   => $data['education_level'] ?? null,
            'availability'      => $data['availability'] ?? null,
            'salary_expectation'=> $data['salary_expectation'] ?? null,
            'linkedin_url'      => $data['linkedin_url'] ?? null,
            'portfolio_url'     => $data['portfolio_url'] ?? null,
            'photo_path'        => $photoPath,
            'cv_path'           => $cvPath,
            'cover_letter'      => $data['cover_letter'] ?? null,
        ]);

        return redirect()->route('jobs')->with('success', 'Your application has been submitted successfully! We will review it and get back to you soon.');
    }
}
